# KPRS
Implementasi KPRS setelah melakukan BPI

## Clone Repo
   ```bash
   # buat folder dan buka command prompt pada file directory tersebut

   # clone repo dengan git command berikut:
   $ git clone https://github.com/AqbilBarakaa/KPRS.git

   # masuk ke folder project
   $ cd KPRS

   # Buka file ke text editor
   $ code .
   ```

## Cara Push File ke Github
   ```bash
   # sebelum melakukan push, utamakan pull file terlebih dahulu untuk memperbarui file dari github ke text editor anda
   $ git pull origin main

   # setelah itu tambah file yang diinginkan
   $ git add nama_file.ext (contoh: git add login.php)

   # jika ingin push semua file
   $ git add .

   # kemudian berikan message dari apa yang di push
   $ git commit -m "add file (..)"

   # push file ke github
   $ git push origin main

   # jika tidak bisa lakukan push paksa
   $ git push origin main --force
   ```
