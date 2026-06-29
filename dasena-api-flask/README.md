# 🐍 DASENA API (Flask) - Damkar Analisis Sentimen API

Aplikasi API yang dikembangkan menggunakan Flask dan Python untuk menjalankan model Natural Language Processing (NLP) dalam menganalisis sentimen publik terhadap layanan Pemadam Kebakaran (Damkar).

---

## 🎯 Fungsi Utama

* **Endpoint Analisis Sentimen:** Menyediakan endpoint POST untuk menerima input teks dan mengembalikan klasifikasi sentimen (Positif, Negatif, Netral).
* **Integrasi Model:** Menghosting dan menjalankan model machine learning/deep learning untuk klasifikasi sentimen.
* **Preprocessing Data:** Menangani proses *tokenization*, *stemming*, dan *cleaning* data sebelum analisis.

## 🛠️ Persyaratan Sistem

* Python 3.x
* Pip
* Virtual Environment

## 📦 Instalasi

1.  **Aktifkan Lingkungan Virtual:**
    ```bash
    source venv/bin/activate
    # Atau di Windows: .\venv\Scripts\activate
    ```
2.  **Instal Dependensi:**
    ```bash
    pip install -r requirements.txt
    ```

## ▶️ Cara Menjalankan Server

Jalankan API Flask di port yang berbeda dari Laravel (misalnya Port 5000):

```bash
flask run --port=5000