from flask import Flask, render_template, request  # type: ignore[reportMissingImports]



app = Flask(__name__)



@app.route('/', methods=['GET', 'POST'])

def index():

    hasil_berat = None

    hasil_uang = None

    luas_input = None

    tanaman_input = None

    nama_lahan = ""



    if request.method == 'POST':

        tanaman_input = request.form['tanaman']

        luas_input = float(request.form['luas'])



        # Menentukan nama label lahan

        if luas_input == 600:

            nama_lahan = "Sawah Pribadi (600 m²)"

        elif luas_input == 900:

            nama_lahan = "Sawah Hak Pakai (900 m²)"

        elif luas_input == 1500:

            nama_lahan = "Sawah Gadai (1500 m²)"



        # === MEMASUKKAN DATA FORECAST REAL SINKRON DENGAN GRAFIK STATISTIK ===

       

        # 1. KOMODITAS JAGUNG (Rp 3.000 / KG)

        if tanaman_input == 'Jagung':

            if luas_input == 1500:

                hasil_berat = 430  

            elif luas_input == 900:

                hasil_berat = 275  

            elif luas_input == 600:

                hasil_berat = 180  

            hasil_uang = hasil_berat * 3000



        # 2. KOMODITAS SINGKONG (Rp 1.000 / KG)

        elif tanaman_input == 'Singkong':

            if luas_input == 1500:

                hasil_berat = 800  

            elif luas_input == 900:

                hasil_berat = 500  

            elif luas_input == 600:

                hasil_berat = 350  

            hasil_uang = hasil_berat * 1000



        # 3. KOMODITAS UBI MERAH (Rp 2.500 / KG)

        elif tanaman_input == 'Ubi Merah':

            if luas_input == 1500:

                hasil_berat = 510  

            elif luas_input == 900:

                hasil_berat = 320  

            elif luas_input == 600:

                hasil_berat = 220  

            hasil_uang = hasil_berat * 2500



        # 4. KOMODITAS UBI UNGU (Rp 2.500 / KG)

        elif tanaman_input == 'Ubi Ungu':

            if luas_input == 1500:

                hasil_berat = 465  

            elif luas_input == 900:

                hasil_berat = 290  

            elif luas_input == 600:

                hasil_berat = 195  

            hasil_uang = hasil_berat * 2500



        # 5. KOMODITAS UBI PUTIH (Rp 2.500 / KG)

        elif tanaman_input == 'Ubi Putih':

            if luas_input == 1500:

                hasil_berat = 435  

            elif luas_input == 900:

                hasil_berat = 270  

            elif luas_input == 600:

                hasil_berat = 180  

            hasil_uang = hasil_berat * 2500



        # 6. KOMODITAS BENGKOANG (Rp 5.000 / KG)

        elif tanaman_input == 'Bengkoang':

            if luas_input == 1500:

                hasil_berat = 360  

            elif luas_input == 900:

                hasil_berat = 230  

            elif luas_input == 600:

                hasil_berat = 150  

            hasil_uang = hasil_berat * 5000



        # Format ribuan rupiah dan KG Indonesia

        hasil_berat = "{:,.0f}".format(hasil_berat).replace(",", ".")

        hasil_uang = "{:,.0f}".format(hasil_uang).replace(",", ".")



        return render_template('index.html',

                               berat=hasil_berat,

                               uang=hasil_uang,

                               lahan=nama_lahan,

                               tanaman=tanaman_input)



    return render_template('index.html', berat=hasil_berat, uang=hasil_uang)



if __name__ == '__main__':

    app.run(debug=True)