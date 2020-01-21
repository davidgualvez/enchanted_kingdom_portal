@extends('layouts.customers.main')
@section('title','Ticket Scanner')


@section('css')
    <!-- <link rel="stylesheet" href="/css/pages/ticket/milligram.min.css">
    <link rel="stylesheet" href="/css/pages/ticket/normalize.css"> -->
@endsection

@section('js') 
    <!-- <script src="/js/pages/ticket/zxing.js"></script>
    <script>
        window.addEventListener('load', function () {
        let selectedDeviceId;
        const codeReader = new ZXing.BrowserMultiFormatReader()
        console.log('ZXing code reader initialized')
        codeReader.getVideoInputDevices()
            .then((videoInputDevices) => {
            const sourceSelect = document.getElementById('sourceSelect')
            selectedDeviceId = videoInputDevices[0].deviceId

            if (videoInputDevices.length >= 1) {
                videoInputDevices.forEach((element) => {
                const sourceOption = document.createElement('option')
                sourceOption.text = element.label
                sourceOption.value = element.deviceId
                sourceSelect.appendChild(sourceOption)
                })

                sourceSelect.onchange = () => {
                selectedDeviceId = sourceSelect.value;
                };

                const sourceSelectPanel = document.getElementById('sourceSelectPanel')
                sourceSelectPanel.style.display = 'block'
            }

            document.getElementById('startButton').addEventListener('click', () => {
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                if (result) {
                    console.log(result);
                    document.getElementById('result').textContent = result.text;
                    
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err)
                    document.getElementById('result').textContent = err
                }
                })
                console.log(`Started continous decode from camera with id ${selectedDeviceId}`)
            })

            document.getElementById('resetButton').addEventListener('click', () => {
                codeReader.reset()
                document.getElementById('result').textContent = '';
                console.log('Reset.')
            })

            })
            .catch((err) => {
            console.error(err)
            });
        });
    </script> -->

    <script>
        $('.message .close')
            .on('click', function() {
                $(this)
                .closest('.message')
                .transition('fade')
                ;
            })
        ;
    </script>
@endsection

@section('content')
    <!-- <main class="wrapper" style="padding-top:2em">

    <section class="container" id="demo-content">
    <h1 class="title">Scan 1D/2D Code from Video Camera</h1>
    <div style="">
        <a class="button" id="startButton">Start</a>
        <a class="button" id="resetButton">Reset</a>
    </div>

    <div id="vid-container">
        <video id="video" width="300" height="300" style="border: 1px solid gray;"></video>
    </div>

    <div id="sourceSelectPanel" style="display:none">
        <label for="sourceSelect">Change video source:</label>
        <select id="sourceSelect" style="max-width:400px">
        </select>
    </div>

    <label>Result:</label>
    <pre><code id="result"></code></pre>

    </section> 
    </main> -->

    <br>
    <form method="post" action="{{ route('ticket-scanner') }}" class="ui form">
        @csrf
        <div class="field">
            <label>Place Your Ticket Number Here to Scan for Validity.</label>
            <input type="text" name="barcode" placeholder="Ticket Number...">
        </div> 
        <div class="ui container center aligned">
            <button class="ui button" type="submit">Click here to scan</button>
        </div>
        
    </form>

    <br> 
    <div class="ui container center aligned">
        <div class="ui green message">
            <i class="close icon"></i>
            <div class="header">
                Valid Ticket
            </div>
            <p>until <b>yyyy-mm-dd</b></p>
            <!-- <hr> -->
            <p>Consumable Qty Balance : <b>0</b></p>
        </div>

        <div class="ui red message">
            <i class="close icon"></i>
            <div class="header">
                Invalid Ticket
            </div>
            <p>expired since <b>yyyy-mm-dd</b></p>
            <p>date consumed : <b>yyyy-mm-dd</b></p>
        </div> 

@endsection