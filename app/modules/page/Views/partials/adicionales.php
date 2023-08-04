<div id="sin_conexion2" style="background:rgba(0,0,0,0.7); height:100vh; width:100%; position:fixed; top:0; z-index:9999; display:none;">
</div>
<div align="center" id="sin_conexion" style="display:none; background: #FF0000; color:#FFFFFF; padding:20px; font-family:Arial, Helvetica, sans-serif; font-size:18px; position:fixed; width:100%; top:30%; z-index:99999;">

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none; z-index:99999;">
        <symbol id="check-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" fill="842029;" role="img" aria-label="Danger:">
            <use xlink:href="#exclamation-triangle-fill" />
        </svg>
        <div>
            <strong> Sin conexi&oacute;n a internet, no se puede guardar.<br /><br />Verifique su conexi&oacute;n a internet y recargue la p&aacute;gina</strong>
        </div>
    </div>
</div>
<div id="content-loader" class="content-loader">

    <div class="loader" id="loader">
        <div class="loader__bar"></div>
        <div class="loader__bar"></div>
        <div class="loader__bar"></div>
        <div class="loader__bar"></div>
        <div class="loader__bar"></div>
        <div class="loader__ball"></div>
    </div>
</div>
<style>
    .content-loader {
        background: rgb(255 255 255 / 70%);
        height: 100vh;
        width: 100%;
        position: fixed;
        top: 0;
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center ;
    }



    .loader {
        position: relative;
        width: 75px;
        height: 100px;
        display: none;
    }

    .loader__bar {
        position: absolute;
        bottom: 0;
        width: 10px;
        height: 50%;
        background: var(--primary);
        transform-origin: center bottom;
        box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.2);
    }

    .loader__bar:nth-child(1) {
        left: 0px;
        transform: scale(1, 0.2);
        -webkit-animation: barUp1 4s infinite;
        animation: barUp1 4s infinite;
    }

    .loader__bar:nth-child(2) {
        left: 15px;
        transform: scale(1, 0.4);
        -webkit-animation: barUp2 4s infinite;
        animation: barUp2 4s infinite;
    }

    .loader__bar:nth-child(3) {
        left: 30px;
        transform: scale(1, 0.6);
        -webkit-animation: barUp3 4s infinite;
        animation: barUp3 4s infinite;
    }

    .loader__bar:nth-child(4) {
        left: 45px;
        transform: scale(1, 0.8);
        -webkit-animation: barUp4 4s infinite;
        animation: barUp4 4s infinite;
    }

    .loader__bar:nth-child(5) {
        left: 60px;
        transform: scale(1, 1);
        -webkit-animation: barUp5 4s infinite;
        animation: barUp5 4s infinite;
    }

    .loader__ball {
        position: absolute;
        bottom: 10px;
        left: 0;
        width: 10px;
        height: 10px;
        background:  var(--primary);
        border-radius: 50%;
        -webkit-animation: ball624 4s infinite;
        animation: ball624 4s infinite;
    }

    @keyframes ball624 {
        0% {
            transform: translate(0, 0);
        }

        5% {
            transform: translate(8px, -14px);
        }

        10% {
            transform: translate(15px, -10px);
        }

        17% {
            transform: translate(23px, -24px);
        }

        20% {
            transform: translate(30px, -20px);
        }

        27% {
            transform: translate(38px, -34px);
        }

        30% {
            transform: translate(45px, -30px);
        }

        37% {
            transform: translate(53px, -44px);
        }

        40% {
            transform: translate(60px, -40px);
        }

        50% {
            transform: translate(60px, 0);
        }

        57% {
            transform: translate(53px, -14px);
        }

        60% {
            transform: translate(45px, -10px);
        }

        67% {
            transform: translate(37px, -24px);
        }

        70% {
            transform: translate(30px, -20px);
        }

        77% {
            transform: translate(22px, -34px);
        }

        80% {
            transform: translate(15px, -30px);
        }

        87% {
            transform: translate(7px, -44px);
        }

        90% {
            transform: translate(0, -40px);
        }

        100% {
            transform: translate(0, 0);
        }
    }

    @-webkit-keyframes barUp1 {
        0% {
            transform: scale(1, 0.2);
        }

        40% {
            transform: scale(1, 0.2);
        }

        50% {
            transform: scale(1, 1);
        }

        90% {
            transform: scale(1, 1);
        }

        100% {
            transform: scale(1, 0.2);
        }
    }

    @keyframes barUp1 {
        0% {
            transform: scale(1, 0.2);
        }

        40% {
            transform: scale(1, 0.2);
        }

        50% {
            transform: scale(1, 1);
        }

        90% {
            transform: scale(1, 1);
        }

        100% {
            transform: scale(1, 0.2);
        }
    }

    @-webkit-keyframes barUp2 {
        0% {
            transform: scale(1, 0.4);
        }

        40% {
            transform: scale(1, 0.4);
        }

        50% {
            transform: scale(1, 0.8);
        }

        90% {
            transform: scale(1, 0.8);
        }

        100% {
            transform: scale(1, 0.4);
        }
    }

    @keyframes barUp2 {
        0% {
            transform: scale(1, 0.4);
        }

        40% {
            transform: scale(1, 0.4);
        }

        50% {
            transform: scale(1, 0.8);
        }

        90% {
            transform: scale(1, 0.8);
        }

        100% {
            transform: scale(1, 0.4);
        }
    }

    @-webkit-keyframes barUp3 {
        0% {
            transform: scale(1, 0.6);
        }

        100% {
            transform: scale(1, 0.6);
        }
    }

    @keyframes barUp3 {
        0% {
            transform: scale(1, 0.6);
        }

        100% {
            transform: scale(1, 0.6);
        }
    }

    @-webkit-keyframes barUp4 {
        0% {
            transform: scale(1, 0.8);
        }

        40% {
            transform: scale(1, 0.8);
        }

        50% {
            transform: scale(1, 0.4);
        }

        90% {
            transform: scale(1, 0.4);
        }

        100% {
            transform: scale(1, 0.8);
        }
    }

    @keyframes barUp4 {
        0% {
            transform: scale(1, 0.8);
        }

        40% {
            transform: scale(1, 0.8);
        }

        50% {
            transform: scale(1, 0.4);
        }

        90% {
            transform: scale(1, 0.4);
        }

        100% {
            transform: scale(1, 0.8);
        }
    }

    @-webkit-keyframes barUp5 {
        0% {
            transform: scale(1, 1);
        }

        40% {
            transform: scale(1, 1);
        }

        50% {
            transform: scale(1, 0.2);
        }

        90% {
            transform: scale(1, 0.2);
        }

        100% {
            transform: scale(1, 1);
        }
    }

    @keyframes barUp5 {
        0% {
            transform: scale(1, 1);
        }

        40% {
            transform: scale(1, 1);
        }

        50% {
            transform: scale(1, 0.2);
        }

        90% {
            transform: scale(1, 0.2);
        }

        100% {
            transform: scale(1, 1);
        }
    }
</style>