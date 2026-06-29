from flask import Flask, request, jsonify
from flask_cors import CORS
import re
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
import joblib
import os
import numpy as np

app = Flask(__name__)
CORS(app)

# MEMUAT SASTRAWI
print("Memuat dictionary Sastrawi... (Mohon tunggu sebentar)")
factory_stopword = StopWordRemoverFactory()
stopword_remover = factory_stopword.create_stop_word_remover()

factory_stemmer = StemmerFactory()
stemmer = factory_stemmer.create_stemmer()
print("Sastrawi siap!")

# MEMUAT MODEL
print("Memuat Model Naive Bayes & TF-IDF...")
try:
    vectorizer = joblib.load("tfidf_vectorizer.pkl")
    model = joblib.load("model_naive_bayes_terbaik.pkl")
    print("Model Naive Bayes berhasil dimuat dan siap digunakan!")
except Exception as e:
    print(f"ERROR: Gagal memuat model. Pastikan file .pkl ada! Detail: {e}")


def clean_text(text):
    text = str(text)
    text = re.split(r"(?i)\|?\s*(?:translate|ai info)", text)[0]

    if "|" in text:
        parts = text.split("|")
        if len(parts) > 2:
            content_parts = parts[2:]
            valid_parts = []
            for p in content_parts:
                p_clean = p.strip()
                if re.fullmatch(r"[\d\.,]+[KkMmBb]?", p_clean):
                    continue
                if p_clean == "." or p_clean == "":
                    continue
                valid_parts.append(p_clean)
            text = " ".join(valid_parts)

    text = re.sub(r"(?i)\breplying to\b", "", text)
    text = re.sub(r"@[\w_.]*damkar[\w_.]*", " damkar ", text, flags=re.I)
    text = re.sub(r"@[A-Za-z0-9_.]+", "", text)
    text = re.sub(r"#.*", "", text)
    text = re.sub(r"[🎥📸].*", "", text)
    text = re.sub(r"http\S+|www\S+|https\S+", "", text, flags=re.MULTILINE)
    text = re.sub(
        r"(?i)\b(?:video|vid|foto|poto|credit|credits|source|sumber|sc|cr)\s*[:/]\s*.*",
        "",
        text,
    )
    text = re.sub(r"(?i)\b(?:ig|instagram|tiktok|youtube)\s*[:/]\s*\S+.*", "", text)
    text = re.sub(
        r"(?i)\b(?:report by|story ig|baca selengkapnya|selengkapnya|klik link|di bio|dibio)\b.*",
        "",
        text,
    )
    text = re.sub(
        r"(?i)\b\w+\.(?:go\.id|co\.id|ac\.id|or\.id|web\.id|com|id|net|org)(?:/\S*)?.*",
        "",
        text,
    )
    text = re.sub(r"[^a-zA-Z\s]", " ", text)

    cleansed = re.sub(r"\s+", " ", text).strip().lower()
    stopword = stopword_remover.remove(cleansed)
    stemmed = stemmer.stem(stopword)

    return {"cleansed": cleansed, "stopword": stopword, "stemmed": stemmed}


@app.route("/api/preprocess", methods=["POST"])
def preprocess_data():
    try:
        data = request.json.get("data", [])
        results = []
        for item in data:
            item_id = item.get("id")
            teks_asli = item.get("teks", "")
            hasil_bersih = clean_text(teks_asli)

            teks_stopword = hasil_bersih["stopword"]
            teks_stemmed = hasil_bersih["stemmed"]

            hasil_sentimen = "Netral"
            confidences = {"Positif": 0, "Netral": 0, "Negatif": 0}

            if teks_stemmed.strip():
                teks_vektor = vectorizer.transform([teks_stemmed])
                hasil_sentimen = model.predict(teks_vektor)[0]

                if hasattr(model, "predict_proba"):
                    probs = model.predict_proba(teks_vektor)[0]
                    classes = model.classes_
                    for i in range(len(classes)):
                        confidences[classes[i].capitalize()] = round(
                            float(probs[i]) * 100, 1
                        )

            results.append(
                {
                    "id": item_id,
                    "teks_stopword": teks_stopword,
                    "teks_stemmed": teks_stemmed,
                    "sentimen": hasil_sentimen,
                    "confidences": confidences,
                }
            )

        return jsonify({"status": "success", "data": results})

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500


if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0", port=5000)
