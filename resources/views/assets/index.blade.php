@extends('layouts.admin')

@section('content')
    <style>
        .canvas-container {
            position: relative;
            width: 100%;
            border: 1px solid #ddd;
            margin-top: 20px;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: start;
            margin-bottom: 20px;
            gap: 10px;
            /* Menambahkan sedikit ruang antar tombol */
        }

        .toolbar button,
        .toolbar input[type="file"],
        .toolbar select,
        .toolbar input[type="color"],
        .toolbar input[type="text"],
        .toolbar input[type="range"] {
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            /* Membuat sudut tombol lebih bulat */
            font-size: 14px;
            cursor: pointer;
        }

        .toolbar button {
            color: #fff;
            background-color: #007bff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .toolbar input[type="file"] {
            background-color: #f8f9fa;
        }

        .toolbar input[type="color"],
        .toolbar input[type="range"] {
            padding: 0;
        }

        .toolbar input[type="text"] {
            width: auto;
            /* Memastikan input teks tidak terlalu lebar */
        }

        .toolbar select {
            background-color: #f8f9fa;
            color: #495057;
        }

        .toolbar button:hover,
        .toolbar select:hover,
        .toolbar input[type="file"]:hover {
            background-color: #0056b3;
        }

        .toolbar input[type="color"]:hover,
        .toolbar input[type="range"]:hover {
            border: 1px solid #ccc;
        }

        .border {
            border: 1px solid #ccc;
        }

        /* Mengatur tampilan icon */
        .fa {
            margin-right: 5px;
        }
    </style>
    <div class="container mt-5">
        <div class="row">
            <div class="d-flex align-items-center justify-content-between col-6">
                <input type="text" class="form-control me-2" id="inputSite" name="site"
                    placeholder="Masukkan Nama Site" value="{{ old('site') ? old('site') : $site ?? '' }}">
            </div>

            <div class="col-md-12">
                <div class="toolbar mb-3">
                    <!-- Menyesuaikan tombol dengan desain baru -->
                    <input type="file" id="file" class="btn btn-light btn-sm">
                    <button id="clear-canvas" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Clear</button>
                    <button id="add-text" class="btn btn-info btn-sm"><i class="fa fa-font"></i> Teks</button>
                    <select id="font-family" class="btn btn-light btn-sm">
                        <option value="Arial">Arial</option>
                        <option value="Helvetica">Helvetica</option>
                        <option value="Times New Roman">Times New Roman</option>
                    </select>
                    <input type="color" id="text-color" class="btn btn-light btn-sm">
                    <button id="draw-line" class="btn btn-primary btn-sm"><i class="fa fa-pencil-alt"></i> Garis</button>
                    <button id="add-rect" class="btn btn-secondary btn-sm"><i class="far fa-square"></i> Persegi</button>
                    <button id="add-circle" class="btn btn-warning btn-sm"><i class="far fa-circle"></i> Lingkaran</button>
                    <button id="add-arrow" class="btn btn-primary btn-sm"><i class="fas fa-arrow-up"></i> Panah</button>
                    <input type="text" id="textForNewArrow" class="form-control form-control-sm"
                        placeholder="Masukkan teks panah..." style="display: inline-block; width: auto;">
                    <button id="createArrow" class="btn btn-primary btn-sm">Buat Panah Baru</button>
                    <button id="add-triangle" class="btn btn-success btn-sm"><i class="fas fa-play"></i> Segitiga</button>
                    <button id="group-objects" class="btn btn-dark btn-sm"><i class="fas fa-object-group"></i>
                        Group</button>
                    <button id="ungroup-objects" class="btn btn-info btn-sm"><i class="fas fa-object-ungroup"></i>
                        Ungroup</button>
                    <input type="range" id="opacity-slider" min="0" max="1" step="0.01" value="1"
                        class="form-range">
                    <input type="color" id="canvas-bg" class="btn btn-light btn-sm" value="#ffffff"
                        title="Ubah Warna Background">
                    <input type="color" id="object-color" class="btn btn-light btn-sm" title="Ubah Warna Objek">
                    <button id="delete-object" class="btn btn-dark btn-sm"><i class="fas fa-eraser"></i> Hapus
                        Objek</button>
                    <button id="use-pen" class="btn btn-primary btn-sm"><i class="fas fa-pen"></i> Pena</button>
                    <button id="use-brush" class="btn btn-secondary btn-sm"><i class="fas fa-paint-brush"></i> Kuas</button>

                    <button id="bring-to-front" class="btn btn-sm" style="background-color: #28a745; color: white;">Bawa ke
                        Depan</button>
                    <button id="send-to-back" class="btn btn-sm" style="background-color: #dc3545; color: white;">Kirim ke
                        Belakang</button>
                    <button id="send-forward" class="btn btn-sm"
                        style="background-color: #ffc107; color: white;">Maju</button>
                    <button id="send-backward" class="btn btn-sm"
                        style="background-color: #17a2b8; color: white;">Mundur</button>
                    <button id="save-canvas" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>

                <canvas id="canvas" width="1100" height="600" class="border"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = new fabric.Canvas('canvas', {
                preserveObjectStacking: true
            });
            var canvasData = @json(isset($asset) && !empty($asset->canvasData) ? $asset->canvasData : null);
            canvas.backgroundColor = '#ffffff';
            canvas.renderAll();


            canvas.isDrawingMode = 0;
            canvas.freeDrawingBrush.color = "black";
            canvas.freeDrawingBrush.width = 1;
            canvas.renderAll();

            // Fungsi untuk mengaktifkan pena
            document.getElementById('use-pen').onclick = function() {
                canvas.isDrawingMode = 1;
                canvas.freeDrawingBrush = new fabric.PencilBrush(canvas);
                canvas.freeDrawingBrush.color = "black";
                canvas.freeDrawingBrush.width = 1;
            };

            // Fungsi untuk mengaktifkan kuas
            document.getElementById('use-brush').onclick = function() {
                canvas.isDrawingMode = 1;
                canvas.freeDrawingBrush = new fabric.CircleBrush(canvas);
                canvas.freeDrawingBrush.color = "black";
                canvas.freeDrawingBrush.width = 10; // Lebar kuas lebih besar untuk efek kuas
            };

            // Contoh untuk mengganti warna dan lebar pena/kuas
            document.getElementById('object-color').onchange = function() {
                canvas.freeDrawingBrush.color = this.value;
            };

            document.getElementById('opacity-slider').oninput = function() {
                canvas.freeDrawingBrush.width = parseInt(this.value, 10);
            };

            function resetCanvasEvents() {
                canvas.isDrawingMode = false;
                canvas.selection = true;
                canvas.forEachObject(function(o) {
                    o.selectable = true;
                    o.evented = true;
                });
                canvas.off('mouse:down');
                canvas.off('mouse:move');
                canvas.off('mouse:up');
            }

            function addEventListeners() {
                document.getElementById('file').addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function(f) {
                            var data = f.target.result;
                            fabric.Image.fromURL(data, function(img) {
                                var oImg = img.set({
                                    left: 0,
                                    top: 0,
                                    angle: 0
                                }).scale(0.9);
                                canvas.add(oImg).renderAll();
                                var a = canvas.setActiveObject(oImg);
                                var dataURL = canvas.toDataURL({
                                    format: 'png',
                                    quality: 0.8
                                });
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });

                document.getElementById('clear-canvas').addEventListener('click', function() {
                    canvas.clear();
                    canvas.backgroundColor = '#ffffff';
                    canvas.renderAll();
                });

                document.getElementById('add-text').addEventListener('click', function() {
                    resetCanvasEvents();
                    var text = new fabric.IText('Tulis teks di sini...', {
                        left: 50,
                        top: 100,
                        fontFamily: 'Arial',
                        fill: document.getElementById('text-color').value
                    });
                    canvas.add(text);
                });
                document.getElementById('opacity-slider').addEventListener('change', function() {
                    var selectedObject = canvas.getActiveObject();
                    if (selectedObject) {
                        selectedObject.set('opacity', parseFloat(this.value));
                        canvas.renderAll();
                    }
                });

                document.getElementById('font-family').addEventListener('change', function() {
                    var fontFamily = this.value;
                    var object = canvas.getActiveObject();
                    if (object && object.type === 'i-text') {
                        object.set('fontFamily', fontFamily);
                        canvas.renderAll();
                    }
                });

                document.getElementById('text-color').addEventListener('change', function() {
                    var color = this.value;
                    var object = canvas.getActiveObject();
                    if (object && (object.type === 'i-text' || object.type === 'text')) {
                        object.set('fill', color);
                        canvas.renderAll();
                    }
                });
                document.getElementById('draw-line').addEventListener('click', function() {
                    resetCanvasEvents();
                    var line, isDown;
                    canvas.on('mouse:down', function(o) {
                        isDown = true;
                        var pointer = canvas.getPointer(o.e);
                        var points = [pointer.x, pointer.y, pointer.x, pointer.y];
                        line = new fabric.Line(points, {
                            strokeWidth: 2,
                            fill: 'red',
                            stroke: 'red',
                            originX: 'center',
                            originY: 'center'
                        });
                        canvas.add(line);
                    });
                    canvas.on('mouse:move', function(o) {
                        if (!isDown) return;
                        var pointer = canvas.getPointer(o.e);
                        line.set({
                            x2: pointer.x,
                            y2: pointer.y
                        });
                        canvas.renderAll();
                    });

                    canvas.on('mouse:up', function(o) {
                        isDown = false;
                    });
                });
                document.getElementById('add-rect').addEventListener('click', function() {
                    resetCanvasEvents();
                    var rect = new fabric.Rect({
                        left: 100,
                        top: 100,
                        fill: 'yellow',
                        width: 60,
                        height: 70,
                        angle: 0
                    });
                    canvas.add(rect);
                });

                document.getElementById('add-circle').addEventListener('click', function() {
                    resetCanvasEvents();
                    var circle = new fabric.Circle({
                        radius: 30,
                        fill: 'green',
                        left: 100,
                        top: 100
                    });
                    canvas.add(circle);
                });

                document.getElementById('add-arrow').addEventListener('click', function() {
                    resetCanvasEvents();
                    var startX = 50,
                        startY = 100,
                        endX = 200,
                        endY = 100;
                    var angle = Math.atan2(endY - startY, endX - startX);

                    var line = new fabric.Line([startX, startY, endX, endY], {
                        strokeWidth: 3,
                        stroke: 'black',
                    });


                    var arrowLength = 20;
                    var arrowWidth = 20;
                    var triangle = new fabric.Triangle({
                        left: endX,
                        top: endY,
                        originX: 'center',
                        originY: 'center',
                        width: arrowWidth,
                        height: arrowLength,
                        fill: 'black',
                        angle: (angle * 180 / Math.PI) +
                            90
                    });

                    var group = new fabric.Group([line, triangle], {
                        selectable: true,
                    });

                    canvas.add(group);
                });
                document.getElementById('add-triangle').addEventListener('click', function() {
                    resetCanvasEvents();
                    var triangle = new fabric.Triangle({
                        width: 20,
                        height: 30,
                        fill: 'blue',
                        left: 50,
                        top: 50
                    });
                    canvas.add(triangle);
                });

                document.getElementById('group-objects').addEventListener('click', function() {
                    if (!canvas.getActiveObject()) {
                        return;
                    }
                    if (canvas.getActiveObject().type !== 'activeSelection') {
                        return;
                    }
                    canvas.getActiveObject().toGroup();
                    canvas.requestRenderAll();
                });

                document.getElementById('ungroup-objects').addEventListener('click', function() {
                    if (!canvas.getActiveObject()) {
                        return;
                    }
                    if (canvas.getActiveObject().type !== 'group') {
                        return;
                    }
                    canvas.getActiveObject().toActiveSelection();
                    canvas.requestRenderAll();
                });

                document.getElementById('save-canvas').addEventListener('click', function() {
                    const site = document.getElementById('inputSite').value;
                    const jsonCanvas = JSON.stringify(canvas.toJSON());
                    const dataURL = canvas.toDataURL({
                        format: 'png',
                        quality: 0.8
                    });

                    fetch('{{ route('asset.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                image: dataURL,
                                canvas: jsonCanvas,
                                site: site
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                            // Tampilkan Sweet Alert setelah berhasil
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                confirmButtonText: 'Oke'
                            });
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            // Tampilkan Sweet Alert untuk error
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menyimpan.',
                                icon: 'error',
                                confirmButtonText: 'Oke'
                            });
                        });
                });


                document.getElementById('canvas-bg').addEventListener('change', function() {
                    canvas.backgroundColor = this.value;
                    canvas.renderAll();
                });

                document.getElementById('object-color').addEventListener('change', function() {
                    var selectedObject = canvas.getActiveObject();
                    if (selectedObject) {
                        if (selectedObject.type === 'line' || selectedObject.type === 'circle' ||
                            selectedObject.type === 'rect' || selectedObject.type === 'triangle') {
                            selectedObject.set({
                                fill: this.value
                            });
                            canvas.renderAll();
                        }
                    }
                });

                document.getElementById('delete-object').addEventListener('click', function() {
                    var selectedObject = canvas.getActiveObject();
                    if (selectedObject) {
                        canvas.remove(selectedObject);
                    }
                });
            }
            // var canvas = new fabric.Canvas('c');

            function createDoubleEndedArrowWithDynamicText(text, left = 50, top = 100) {
                // Panah
                var line = new fabric.Line([0, 0, 250, 0], {
                    stroke: 'black',
                    strokeWidth: 2,
                    selectable: false,
                });

                // Segitiga di ujung kiri
                var triangleLeft = new fabric.Triangle({
                    width: 20,
                    height: 20,
                    fill: 'black',
                    angle: -90,
                    left: 0,
                    top: 10,
                    selectable: false,
                });

                // Segitiga di ujung kanan
                var triangleRight = new fabric.Triangle({
                    width: 20,
                    height: 20,
                    fill: 'black',
                    angle: 90,
                    left: 250,
                    top: -10,
                    selectable: false,
                });

                // Teks
                var dynamicText = new fabric.Text(text, {
                    fontSize: 20,
                    left: 125 - (text.length * 5), // Posisi tengah panah, disesuaikan dengan panjang teks
                    top: -30,
                    selectable: false,
                });

                // Mengelompokkan elemen panah dan teks
                var arrowWithText = new fabric.Group([line, triangleLeft, triangleRight, dynamicText], {
                    left: left,
                    top: top,
                    selectable: true,
                });

                canvas.add(arrowWithText);
            }

            // Fungsi untuk membuat panah baru dengan teks dari input
            document.getElementById('createArrow').addEventListener('click', function() {
                var newText = document.getElementById('textForNewArrow').value;
                createDoubleEndedArrowWithDynamicText(newText);
            });

            document.getElementById('bring-to-front').addEventListener('click', function() {
                var selectedObject = canvas.getActiveObject();
                if (selectedObject) {
                    selectedObject.bringToFront();
                    canvas.renderAll();
                }
            });

            document.getElementById('send-to-back').addEventListener('click', function() {
                var selectedObject = canvas.getActiveObject();
                if (selectedObject) {
                    selectedObject.sendToBack();
                    canvas.renderAll();
                }
            });
            document.getElementById('send-forward').addEventListener('click', function() {
                var selectedObject = canvas.getActiveObject();
                if (selectedObject) {
                    selectedObject.bringForward();
                    canvas.renderAll();
                }

            });
            document.getElementById('send-backward').addEventListener('click', function() {
                var selectedObject = canvas.getActiveObject();
                if (selectedObject) {
                    selectedObject.sendBackwards();
                    canvas.renderAll();
                }


            });

            // Tambahkan event listener untuk tombol lainnya sesuai kebutuhan




            function loadSavedCanvas() {
                if (canvasData) {
                    canvas.loadFromJSON(canvasData, function() {
                        canvas.renderAll();
                        // Setelah canvas di-render, loop melalui semua objek
                        canvas.getObjects().forEach(function(obj) {
                            if (obj.type === 'image' && obj._element === null) {
                                // Pastikan URL atau src dalam objek gambar valid
                                fabric.Image.fromURL(obj.src, function(img) {
                                    obj.setElement(img.getElement());
                                    canvas.renderAll();
                                });
                            }

                        });
                    });
                }

            }
            loadSavedCanvas();
            addEventListeners();
        });
    </script>
@endpush
