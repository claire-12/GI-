<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style id="applicationStylesheet" type="text/css">
        @font-face {
            font-family: 'Myriad Pro';
            src: url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/Myriad Pro Regular.woff2') format('woff2'),
                url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/Myriad Pro Regular.woff') format('woff');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Myriad Pro';
            src: url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/Myriad Pro Semibold.woff2') format('woff2'),
                url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/Myriad Pro Semibold.woff') format('woff');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'Myriad Pro';
            src: url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/MyriadPro-Black.woff2') format('woff2'),
                url('<?php echo home_url('/home-temp/') ?>fonts/MyriaPro/MyriadPro-Black.woff') format('woff');
            font-weight: 900;
            font-style: normal;
            font-display: swap;
        }
        .mediaViewInfo {
            --web-view-name: Homepage;
            --web-view-id: Homepage;
            --web-scale-on-resize: true;
            --web-enable-deep-linking: true;
        }

        :root {
            --web-view-ids: Homepage;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            border: none;
        }

        #Homepage {
            position: absolute;
            width: 1400px;
            height: 3727px;
            background-color: rgba(255, 255, 255, 1);
            overflow: hidden;
            --web-view-name: Homepage;
            --web-view-id: Homepage;
            --web-scale-on-resize: true;
            --web-enable-deep-linking: true;
        }

        #Group_434 {
            position: absolute;
            width: 1400px;
            height: 911.228px;
            left: 0px;
            top: 2428.772px;
            overflow: visible;
        }

        #Path_311 {
            fill: rgba(251, 251, 251, 1);
        }

        .Path_311 {
            overflow: visible;
            position: absolute;
            width: 1400px;
            height: 911.228px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Menu {
            position: absolute;
            width: 69.215px;
            height: 16.771px;
            left: 1297.911px;
            top: 78.058px;
            overflow: visible;
        }

        #MENU {
            left: 27.722px;
            top: 5.286px;
            position: absolute;
            overflow: visible;
            width: 24px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 9px;
            color: rgba(43, 46, 52, 1);
        }

        #Group_57 {
            position: absolute;
            width: 69.215px;
            height: 16.771px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_404 {
            position: absolute;
            width: 65.932px;
            height: 23.041px;
            left: 1223.532px;
            top: 36.531px;
            overflow: visible;
        }

        #Group_52 {
            position: absolute;
            width: 65.932px;
            height: 23.041px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #SIGN_IN_o {
            left: 18.49px;
            top: 7.063px;
            position: absolute;
            overflow: visible;
            width: 29px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 9px;
            color: rgba(43, 46, 52, 1);
        }

        #SIGN_IN_o {
            left: 18.49px;
            top: 7.063px;
            position: absolute;
            overflow: visible;
            width: 29px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 9px;
            color: rgba(43, 46, 52, 1);
        }

        #Search {
            position: absolute;
            width: 74.961px;
            height: 18.419px;
            left: 1292.165px;
            top: 38.842px;
            overflow: visible;
        }

        #Group_48 {
            position: absolute;
            width: 74.961px;
            height: 18.419px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #SEARCH {
            left: 25.058px;
            top: 5.286px;
            position: absolute;
            overflow: visible;
            width: 31px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 9px;
            color: rgba(43, 46, 52, 1);
        }

        #Group_147 {
            position: absolute;
            width: 314px;
            height: 11px;
            left: 944px;
            top: 84px;
            overflow: visible;
        }

        #Group {
            position: absolute;
            width: 314px;
            height: 11px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Capabilities {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 72px;
            height: 11px;
            line-height: 60px;
            margin-top: -25px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 10px;
            color: rgba(0, 0, 0, 1);
            text-transform: uppercase;
        }

        #PRODUCTS {
            left: 83px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 72px;
            height: 11px;
            line-height: 60px;
            margin-top: -25px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 10px;
            color: rgba(0, 0, 0, 1);
            text-transform: uppercase;
        }

        #INDUSTRIES {
            left: 160px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 72px;
            height: 11px;
            line-height: 60px;
            margin-top: -25px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 10px;
            color: rgba(0, 0, 0, 1);
            text-transform: uppercase;
        }

        #INNOVATION {
            left: 243px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 72px;
            height: 11px;
            line-height: 60px;
            margin-top: -25px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 10px;
            color: rgba(0, 0, 0, 1);
            text-transform: uppercase;
        }

        #Group_150 {
            position: absolute;
            width: 1399.989px;
            height: 494.7px;
            left: 0px;
            top: 118.178px;
            overflow: visible;
        }

        #Group_149 {
            position: absolute;
            width: 1399.989px;
            height: 494.7px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Nav_Arrow {
            position: absolute;
            width: 50.312px;
            height: 50.312px;
            left: 1289.156px;
            top: 340.626px;
            overflow: visible;
        }

        #Group_9 {
            position: absolute;
            width: 50.312px;
            height: 50.312px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_10 {
            position: absolute;
            width: 12.585px;
            height: 25.169px;
            left: 21.525px;
            top: 13.088px;
            overflow: visible;
        }

        #Path_9 {
            fill: rgba(0, 130, 147, 1);
        }

        .Path_9 {
            overflow: visible;
            position: absolute;
            width: 12.585px;
            height: 25.169px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Nav_Arrow_ {
            transform: matrix(1, 0, 0, 1, 60.5203, 340.6265) rotate(180deg);
            transform-origin: center;
            position: absolute;
            width: 50.312px;
            height: 50.312px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_9_ {
            position: absolute;
            width: 50.312px;
            height: 50.312px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_10_ {
            position: absolute;
            width: 12.585px;
            height: 25.169px;
            left: 16.202px;
            top: 12.055px;
            overflow: visible;
        }

        #Path_9_ {
            fill: rgba(0, 130, 147, 1);
        }

        .Path_9_ {
            overflow: visible;
            position: absolute;
            width: 12.585px;
            height: 25.169px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Sustainable_Growth {
            left: 398px;
            top: 287px;
            position: absolute;
            overflow: visible;
            width: 613px;
            height: 64px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 64px;
            color: rgba(255, 255, 255, 1);
        }

        #for_more_than_100_years {
            left: 416px;
            top: 346px;
            position: absolute;
            overflow: visible;
            width: 594px;
            height: 51px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 50px;
            color: rgba(255, 255, 255, 1);
        }

        #Group_205 {
            position: absolute;
            width: 257px;
            height: 59px;
            left: 590px;
            top: 433px;
            overflow: visible;
        }

        #White_hollow_button {
            position: absolute;
            width: 183px;
            height: 25px;
            left: 28.623px;
            top: 17.001px;
            overflow: visible;
        }

        #n_FIND_OUT_MORE {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 184px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(255, 255, 255, 1);
            letter-spacing: 0.2px;
        }

        #Rectangle_24 {
            fill: transparent;
            stroke: rgba(255, 255, 255, 1);
            stroke-width: 3px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Rectangle_24 {
            position: absolute;
            overflow: visible;
            width: 257px;
            height: 59px;
            left: 0px;
            top: 0px;
        }

        #Untitled-28 {
            position: absolute;
            width: 244px;
            height: 45px;
            left: 25px;
            top: 39px;
            overflow: visible;
        }

        #Group_419 {
            position: absolute;
            width: 172px;
            height: 31.718px;
            left: 614px;
            top: 2463.121px;
            overflow: visible;
        }

        #INNOVATION_bg {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 173px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Line_1 {
            fill: transparent;
            stroke: rgba(221, 20, 22, 1);
            stroke-width: 3px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_1 {
            overflow: visible;
            position: absolute;
            width: 35px;
            height: 3px;
            left: 68.177px;
            top: 31.719px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Learn_more_about_our_areas_of_ {
            left: 469.58px;
            top: 2575.88px;
            position: absolute;
            overflow: visible;
            width: 461px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Nimin_nimus_aut_pa_sam_vel_inc {
            left: 67.812px;
            top: 3042.88px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 132px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Nimin_nimus_aut_pa_sam_vel_inc_bk {
            left: 721.812px;
            top: 3042.88px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 132px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Nimin_nimus_aut_pa_sam_vel_inc_bl {
            left: 1348.812px;
            top: 3042.88px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 132px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Group_157 {
            position: absolute;
            width: 700.057px;
            height: 371.143px;
            left: 0px;
            top: 725.663px;
            overflow: visible;
        }

        #Group_154 {
            position: absolute;
            width: 699.994px;
            height: 371.143px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_156 {
            position: absolute;
            width: 700.057px;
            height: 371.143px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_261 {
            position: absolute;
            width: 172px;
            height: 31.571px;
            left: 759px;
            top: 725.81px;
            overflow: visible;
        }

        #CAPABILITIES {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 173px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Line_1_br {
            fill: transparent;
            stroke: rgba(221, 20, 22, 1);
            stroke-width: 3px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_1_br {
            overflow: visible;
            position: absolute;
            width: 35px;
            height: 3px;
            left: 0.421px;
            top: 31.571px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #We_materialize_ideas__for_a_sa {
            left: 759px;
            top: 790.81px;
            position: absolute;
            overflow: visible;
            width: 469px;
            height: 130px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #System-critical_elastomer_comp {
            left: 759px;
            top: 939.81px;
            position: absolute;
            overflow: visible;
            width: 524px;
            height: 20px;
            line-height: 23px;
            margin-top: -1.5px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Red_Button {
            position: absolute;
            width: 202px;
            height: 42px;
            left: 759px;
            top: 995.81px;
            overflow: visible;
        }

        #Rectangle_26 {
            fill: rgba(221, 20, 22, 1);
        }

        .Rectangle_26 {
            position: absolute;
            overflow: visible;
            width: 202px;
            height: 42px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1 {
            fill: rgba(255, 255, 255, 1);
        }

        .Polygon_1 {
            overflow: visible;
            position: absolute;
            width: 24px;
            height: 12px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 154, 14.9999) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #ABOUT_US {
            left: 25px;
            top: 10px;
            position: absolute;
            overflow: visible;
            width: 131px;
            height: 31px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(255, 255, 255, 1);
            letter-spacing: 0.2px;
        }

        #Material_Innovation__Solutions {
            left: 67.812px;
            top: 2937.36px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 85px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Smart_Material_and__Sensor_Sol {
            left: 721.812px;
            top: 2937.36px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 85px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Innovation_at_the_heart_of_wha {
            left: 364px;
            top: 2514.76px;
            position: absolute;
            overflow: visible;
            width: 673px;
            height: 40px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Hydrogen_and__Battery_Solution {
            left: 1348.812px;
            top: 2937.36px;
            position: absolute;
            overflow: visible;
            width: 628px;
            height: 85px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Group_283 {
            position: absolute;
            width: 172px;
            height: 31.718px;
            left: 613.953px;
            top: 1836.121px;
            overflow: visible;
        }

        #INDUSTRIES_b {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 173px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Line_1_b {
            fill: transparent;
            stroke: rgba(221, 20, 22, 1);
            stroke-width: 3px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_1_b {
            overflow: visible;
            position: absolute;
            width: 35px;
            height: 3px;
            left: 68.177px;
            top: 31.719px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Serving_four_main_industries {
            left: 469.58px;
            top: 1948.88px;
            position: absolute;
            overflow: visible;
            width: 461px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Red_Button_b {
            position: absolute;
            width: 322.289px;
            height: 41.562px;
            left: 538.85px;
            top: 1991.171px;
            overflow: visible;
        }

        #Rectangle_26_b {
            fill: transparent;
            stroke: rgba(0, 0, 0, 1);
            stroke-width: 1px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Rectangle_26_b {
            position: absolute;
            overflow: visible;
            width: 322.289px;
            height: 41.562px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1_b {
            fill: rgba(0, 0, 0, 1);
        }

        .Polygon_1_b {
            overflow: visible;
            position: absolute;
            width: 24.791px;
            height: 12.396px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 286.9248, 14.9478) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #VIEW_OUR_INDUSTRIES {
            left: 21.146px;
            top: 9.479px;
            position: absolute;
            overflow: visible;
            width: 272.977px;
            height: 29.89559555053711px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(0, 0, 0, 1);
            letter-spacing: 0.2px;
        }

        #Strategic_development_partner {
            left: 334.685px;
            top: 1888.36px;
            position: absolute;
            overflow: visible;
            width: 732px;
            height: 40px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Group_282 {
            position: absolute;
            width: 61.614px;
            height: 31.354px;
            left: 67.812px;
            top: 2075.754px;
            overflow: visible;
        }

        #Rectangle_27 {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27 {
            position: absolute;
            overflow: visible;
            width: 61.614px;
            height: 31.354px;
            left: 0px;
            top: 0px;
        }

        #HVAC {
            left: 6.198px;
            top: 6.563px;
            position: absolute;
            overflow: visible;
            width: 56px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER {
            position: absolute;
            width: 101.06px;
            height: 31.354px;
            left: 388.64px;
            top: 2075.354px;
            overflow: visible;
        }

        #Rectangle_27_cf {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_cf {
            position: absolute;
            overflow: visible;
            width: 101.06px;
            height: 31.354px;
            left: 0px;
            top: 0px;
        }

        #OIL__GAS {
            left: 6.201px;
            top: 6.963px;
            position: absolute;
            overflow: visible;
            width: 86px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_ch {
            position: absolute;
            width: 73.201px;
            height: 31.354px;
            left: 718.926px;
            top: 2073.956px;
            overflow: visible;
        }

        #Rectangle_27_ci {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_ci {
            width: 73.201px;
            height: 31.354px;
            position: absolute;
            overflow: visible;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 0, 0) rotate(0deg);
            transform-origin: center;
        }

        #WATER {
            left: 6.201px;
            top: 6.964px;
            position: absolute;
            overflow: visible;
            width: 68px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_ck {
            position: absolute;
            width: 143.201px;
            height: 31.354px;
            left: 1020.096px;
            top: 2073.956px;
            overflow: visible;
        }

        #Rectangle_27_cl {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_cl {
            width: 142.851px;
            height: 31.354px;
            position: absolute;
            overflow: visible;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 0, 0) rotate(0deg);
            transform-origin: center;
        }

        #POWER_TOOLS {
            left: 6.201px;
            top: 6.964px;
            position: absolute;
            overflow: visible;
            width: 138px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #Group_286 {
            position: absolute;
            width: 282.238px;
            height: 279.141px;
            left: 67.812px;
            top: 2107.108px;
            overflow: visible;
        }

        #Group_285 {
            position: absolute;
            width: 282.238px;
            height: 279.141px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_289 {
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 388.64px;
            top: 2107.108px;
            overflow: visible;
        }

        #Group_288 {
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_291 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 718.926px;
            top: 2105.31px;
            overflow: visible;
        }

        #Group_290 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Rectangle_35 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_294 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 1020.096px;
            top: 2105.31px;
            overflow: visible;
        }

        #Group_293 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_403 {
            position: absolute;
            width: 1432.437px;
            height: 387px;
            left: 0px;
            top: 3340px;
            overflow: visible;
        }

        #Rectangle_38 {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_38 {
            position: absolute;
            overflow: visible;
            width: 1401px;
            height: 387px;
            left: 0px;
            top: 0px;
        }

        #Group_368 {
            position: absolute;
            width: 200.492px;
            height: 37.205px;
            left: 71.458px;
            top: 37.02px;
            overflow: visible;
        }

        #Group_349 {
            position: absolute;
            width: 7.442px;
            height: 7.442px;
            left: 11.161px;
            top: 2.48px;
            overflow: visible;
        }

        #Path_265 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_265 {
            overflow: visible;
            position: absolute;
            width: 7.442px;
            height: 7.442px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_350 {
            position: absolute;
            width: 18.602px;
            height: 18.602px;
            left: 0px;
            top: 18.604px;
            overflow: visible;
        }

        #Path_266 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_266 {
            overflow: visible;
            position: absolute;
            width: 18.602px;
            height: 18.602px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_351 {
            position: absolute;
            width: 9.921px;
            height: 9.92px;
            left: 8.682px;
            top: 7.442px;
            overflow: visible;
        }

        #Path_267 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_267 {
            overflow: visible;
            position: absolute;
            width: 9.921px;
            height: 9.919px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_352 {
            position: absolute;
            width: 14.881px;
            height: 14.884px;
            left: 24.805px;
            top: 18.602px;
            overflow: visible;
        }

        #Path_268 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_268 {
            overflow: visible;
            position: absolute;
            width: 14.881px;
            height: 14.884px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_353 {
            position: absolute;
            width: 13.643px;
            height: 13.642px;
            left: 21.082px;
            top: 7.441px;
            overflow: visible;
        }

        #Path_269 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_269 {
            overflow: visible;
            position: absolute;
            width: 13.643px;
            height: 13.642px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_354 {
            position: absolute;
            width: 9.921px;
            height: 9.921px;
            left: 18.603px;
            top: 9.923px;
            overflow: visible;
        }

        #Path_270 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_270 {
            overflow: visible;
            position: absolute;
            width: 9.921px;
            height: 9.921px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_355 {
            position: absolute;
            width: 9.921px;
            height: 9.92px;
            left: 18.603px;
            top: 24.805px;
            overflow: visible;
        }

        #Path_271 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_271 {
            overflow: visible;
            position: absolute;
            width: 9.921px;
            height: 9.919px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_356 {
            position: absolute;
            width: 14.882px;
            height: 14.882px;
            left: 18.604px;
            top: 0px;
            overflow: visible;
        }

        #Path_272 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_272 {
            overflow: visible;
            position: absolute;
            width: 14.882px;
            height: 14.882px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_357 {
            position: absolute;
            width: 4.961px;
            height: 4.96px;
            left: 2.48px;
            top: 16.123px;
            overflow: visible;
        }

        #Path_273 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_273 {
            overflow: visible;
            position: absolute;
            width: 4.961px;
            height: 4.96px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_359 {
            position: absolute;
            width: 200.492px;
            height: 37.205px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_360 {
            position: absolute;
            width: 16.119px;
            height: 18.375px;
            left: 84.836px;
            top: 9.414px;
            overflow: visible;
        }

        #Path_276 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_276 {
            overflow: visible;
            position: absolute;
            width: 16.119px;
            height: 18.375px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_361 {
            position: absolute;
            width: 47.222px;
            height: 18.56px;
            left: 101.742px;
            top: 9.36px;
            overflow: visible;
        }

        #Path_277 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_277 {
            overflow: visible;
            position: absolute;
            width: 47.222px;
            height: 18.56px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_362 {
            position: absolute;
            width: 13.993px;
            height: 18.375px;
            left: 150.327px;
            top: 9.413px;
            overflow: visible;
        }

        #Path_278 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_278 {
            overflow: visible;
            position: absolute;
            width: 13.993px;
            height: 18.375px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_363 {
            position: absolute;
            width: 14.911px;
            height: 18.375px;
            left: 166.288px;
            top: 9.414px;
            overflow: visible;
        }

        #Path_279 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_279 {
            overflow: visible;
            position: absolute;
            width: 14.911px;
            height: 18.375px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_365 {
            position: absolute;
            width: 200.492px;
            height: 37.205px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_366 {
            position: absolute;
            width: 8.798px;
            height: 4.6px;
            left: 71.974px;
            top: 9.281px;
            overflow: visible;
        }

        #Path_282 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_282 {
            overflow: visible;
            position: absolute;
            width: 8.798px;
            height: 4.6px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_367 {
            position: absolute;
            width: 20.555px;
            height: 12.154px;
            left: 66.096px;
            top: 15.635px;
            overflow: visible;
        }

        #Path_283 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_283 {
            overflow: visible;
            position: absolute;
            width: 20.555px;
            height: 12.154px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Terms_of_Service {
            left: 71px;
            top: 104px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Sitemap {
            left: 275px;
            top: 104px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #News {
            left: 480px;
            top: 104px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Datwyler_Group {
            left: 866px;
            top: 104px;
            position: absolute;
            overflow: visible;
            width: 190px;
            height: 20px;
            line-height: 60px;
            margin-top: -20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
            text-decoration: underline;
        }

        #Mobility {
            left: 866px;
            top: 151px;
            position: absolute;
            overflow: visible;
            width: 190px;
            height: 20px;
            line-height: 60px;
            margin-top: -20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
            text-decoration: underline;
        }

        #Healthcare {
            left: 1136px;
            top: 151px;
            position: absolute;
            overflow: visible;
            width: 190px;
            height: 20px;
            line-height: 60px;
            margin-top: -20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
            text-decoration: underline;
        }

        #Connectors {
            left: 866px;
            top: 197px;
            position: absolute;
            overflow: visible;
            width: 190px;
            height: 20px;
            line-height: 60px;
            margin-top: -20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
            text-decoration: underline;
        }

        #Food__Beverage {
            left: 1136px;
            top: 197px;
            position: absolute;
            overflow: visible;
            width: 190px;
            height: 20px;
            line-height: 60px;
            margin-top: -20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
            text-decoration: underline;
        }

        #Cookie_Consent {
            left: 71px;
            top: 136px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Contact {
            left: 275px;
            top: 136px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Keep_Me_Informed {
            left: 480px;
            top: 136px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Data_Privacy {
            left: 71px;
            top: 171px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Our_brands {
            left: 71px;
            top: 265px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Follow_us {
            left: 866px;
            top: 265px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Careers {
            left: 275px;
            top: 171px;
            position: absolute;
            overflow: visible;
            width: 143px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Environmental_Policy {
            left: 480px;
            top: 171px;
            position: absolute;
            overflow: visible;
            width: 185px;
            height: 19px;
            line-height: 60px;
            margin-top: -21px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 18px;
            color: rgba(255, 255, 255, 1);
        }

        #Line_2 {
            fill: transparent;
            stroke: rgba(255, 255, 255, 1);
            stroke-width: 1px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_2 {
            overflow: visible;
            position: absolute;
            width: 566.558px;
            height: 1px;
            left: 71.822px;
            top: 291.115px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Line_3 {
            fill: transparent;
            stroke: rgba(255, 255, 255, 1);
            stroke-width: 1px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_3 {
            overflow: visible;
            position: absolute;
            width: 566.558px;
            height: 1px;
            left: 865.878px;
            top: 291.115px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_371 {
            position: absolute;
            width: 81.276px;
            height: 36.828px;
            left: 71.458px;
            top: 306.427px;
            overflow: visible;
        }

        #Group_370 {
            position: absolute;
            width: 81.276px;
            height: 36.828px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_369 {
            position: absolute;
            width: 81.276px;
            height: 36.828px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_374 {
            position: absolute;
            width: 126.144px;
            height: 32.881px;
            left: 175.729px;
            top: 310.374px;
            overflow: visible;
        }

        #Group_373 {
            position: absolute;
            width: 126.144px;
            height: 32.881px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_372 {
            position: absolute;
            width: 126.144px;
            height: 32.881px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_377 {
            position: absolute;
            width: 126.144px;
            height: 34.02px;
            left: 340.518px;
            top: 310.375px;
            overflow: visible;
        }

        #Group_376 {
            position: absolute;
            width: 126.144px;
            height: 34.02px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_375 {
            position: absolute;
            width: 126.144px;
            height: 34.02px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_380 {
            position: absolute;
            width: 141.12px;
            height: 34.315px;
            left: 486.35px;
            top: 310.375px;
            overflow: visible;
        }

        #Group_379 {
            position: absolute;
            width: 141.12px;
            height: 34.315px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_378 {
            position: absolute;
            width: 141.12px;
            height: 34.315px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_383 {
            position: absolute;
            width: 14.566px;
            height: 27.403px;
            left: 865.878px;
            top: 317.365px;
            overflow: visible;
        }

        #Group_382 {
            position: absolute;
            width: 14.566px;
            height: 27.403px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_389 {
            position: absolute;
            width: 21.442px;
            height: 21.442px;
            left: 902.445px;
            top: 320.346px;
            overflow: visible;
        }

        #Group_400 {
            position: absolute;
            width: 21.442px;
            height: 21.442px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_385 {
            position: absolute;
            width: 21.442px;
            height: 21.442px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Path_287 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_287 {
            overflow: visible;
            position: absolute;
            width: 21.442px;
            height: 21.442px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_386 {
            position: absolute;
            width: 11.01px;
            height: 11.01px;
            left: 5.216px;
            top: 5.215px;
            overflow: visible;
        }

        #Path_288 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_288 {
            overflow: visible;
            position: absolute;
            width: 11.01px;
            height: 11.01px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_387 {
            position: absolute;
            width: 2.573px;
            height: 2.572px;
            left: 15.157px;
            top: 3.712px;
            overflow: visible;
        }

        #Path_289 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_289 {
            overflow: visible;
            position: absolute;
            width: 2.573px;
            height: 2.573px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_393 {
            position: absolute;
            width: 27.398px;
            height: 17.91px;
            left: 939.982px;
            top: 322.963px;
            overflow: visible;
        }

        #Group_401 {
            position: absolute;
            width: 27.398px;
            height: 17.91px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_390 {
            position: absolute;
            width: 1px;
            height: 1px;
            left: 27.398px;
            top: 8.103px;
            overflow: visible;
        }

        #Path_291 {
            fill: rgba(89, 91, 96, 1);
        }

        .Path_291 {
            overflow: visible;
            position: absolute;
            width: 1px;
            height: 1px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_391 {
            position: absolute;
            width: 22.037px;
            height: 17.91px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Path_292 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_292 {
            overflow: visible;
            position: absolute;
            width: 22.037px;
            height: 17.91px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_399 {
            position: absolute;
            width: 20.18px;
            height: 20.125px;
            left: 980.847px;
            top: 320.245px;
            overflow: visible;
        }

        #Group_402 {
            position: absolute;
            width: 20.18px;
            height: 20.125px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_394 {
            position: absolute;
            width: 1px;
            height: 1px;
            left: -980.847px;
            top: -3660.245px;
            overflow: visible;
        }

        #Group_395 {
            position: absolute;
            width: 13.044px;
            height: 13.772px;
            left: 7.136px;
            top: 6.352px;
            overflow: visible;
        }

        #Path_295 {
            fill: rgba(251, 251, 251, 1);
        }

        .Path_295 {
            overflow: visible;
            position: absolute;
            width: 13.044px;
            height: 13.772px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_396 {
            position: absolute;
            width: 4.19px;
            height: 13.442px;
            left: 0.32px;
            top: 6.683px;
            overflow: visible;
        }

        #Path_296 {
            fill: rgba(251, 251, 251, 1);
        }

        .Path_296 {
            overflow: visible;
            position: absolute;
            width: 4.19px;
            height: 13.442px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_397 {
            position: absolute;
            width: 4.839px;
            height: 4.837px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Path_297 {
            fill: rgba(251, 251, 251, 1);
        }

        .Path_297 {
            overflow: visible;
            position: absolute;
            width: 4.839px;
            height: 4.837px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_413 {
            position: absolute;
            width: 84.972px;
            height: 24.623px;
            left: 1123.05px;
            top: 36.199px;
            overflow: visible;
        }

        #MY_QUOTES_3 {
            left: 27.972px;
            top: 7.395px;
            position: absolute;
            overflow: visible;
            width: 58px;
            white-space: nowrap;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 9px;
            color: rgba(43, 46, 52, 1);
        }

        #Group_412 {
            position: absolute;
            width: 22.964px;
            height: 24.623px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_411 {
            position: absolute;
            width: 22.964px;
            height: 24.623px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_432 {
            position: absolute;
            width: 1401px;
            height: 43px;
            left: 0.5px;
            top: 641.44px;
            overflow: visible;
        }

        #Line_4 {
            fill: transparent;
            stroke: rgba(112, 112, 112, 1);
            stroke-width: 1px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_4 {
            overflow: visible;
            position: absolute;
            width: 1401px;
            height: 1px;
            left: 0px;
            top: 43px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #JUMP_TO {
            left: 329.5px;
            top: 18px;
            position: absolute;
            overflow: visible;
            width: 87px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #GREY_HEADER_fl {
            position: absolute;
            width: 130.883px;
            height: 43px;
            left: 422.5px;
            top: 0px;
            overflow: visible;
        }

        #Rectangle_27_fm {
            fill: rgba(221, 20, 22, 1);
        }

        .Rectangle_27_fm {
            position: absolute;
            overflow: visible;
            width: 130.883px;
            height: 43px;
            left: 0px;
            top: 0px;
        }

        #CAPABILITIES_fn {
            left: 7px;
            top: 13px;
            position: absolute;
            overflow: hidden;
            width: 117px;
            height: 17px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_fo {
            position: absolute;
            width: 139.383px;
            height: 33.385px;
            left: 567.5px;
            top: 9.615px;
            overflow: visible;
        }

        #Rectangle_27_fp {
            fill: rgba(51, 51, 51, 1);
        }

        .Rectangle_27_fp {
            position: absolute;
            overflow: visible;
            width: 139.383px;
            height: 33.385px;
            left: 0px;
            top: 0px;
        }

        #PRODUCTS_fq {
            left: 7px;
            top: 8.193px;
            position: absolute;
            overflow: hidden;
            width: 125.5px;
            height: 17px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_fr {
            position: absolute;
            width: 139.383px;
            height: 33.385px;
            left: 720.5px;
            top: 9.615px;
            overflow: visible;
        }

        #Rectangle_27_fs {
            fill: rgba(51, 51, 51, 1);
        }

        .Rectangle_27_fs {
            position: absolute;
            overflow: visible;
            width: 139.383px;
            height: 33.385px;
            left: 0px;
            top: 0px;
        }

        #INDUSTRIES_ft {
            left: 7px;
            top: 8.193px;
            position: absolute;
            overflow: visible;
            width: 125.5px;
            height: 25.19268798828125px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_fu {
            position: absolute;
            width: 139.383px;
            height: 33.385px;
            left: 874.5px;
            top: 9.615px;
            overflow: visible;
        }

        #Rectangle_27_fv {
            fill: rgba(51, 51, 51, 1);
        }

        .Rectangle_27_fv {
            position: absolute;
            overflow: visible;
            width: 139.383px;
            height: 33.385px;
            left: 0px;
            top: 0px;
        }

        #INNOVATION_fw {
            left: 7px;
            top: 8.193px;
            position: absolute;
            overflow: visible;
            width: 125.5px;
            height: 25.19268798828125px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #Group_162 {
            position: absolute;
            width: 1399.989px;
            height: 656.87px;
            left: 0px;
            top: 1138.028px;
            overflow: visible;
        }

        #Path_136 {
            fill: rgba(251, 251, 251, 1);
        }

        .Path_136 {
            overflow: visible;
            position: absolute;
            width: 1399.989px;
            height: 656.87px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_262 {
            position: absolute;
            width: 172px;
            height: 31.718px;
            left: 613.953px;
            top: 1169.263px;
            overflow: visible;
        }

        #PRODUCTS_f {
            left: 0px;
            top: 0px;
            position: absolute;
            overflow: visible;
            width: 173px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Line_1_f {
            fill: transparent;
            stroke: rgba(221, 20, 22, 1);
            stroke-width: 3px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Line_1_f {
            overflow: visible;
            position: absolute;
            width: 35px;
            height: 3px;
            left: 68.177px;
            top: 31.719px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Ga_Comnimpori_dolutestem_re_se {
            left: 470px;
            top: 1290.434px;
            position: absolute;
            overflow: visible;
            width: 461px;
            height: 20px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(0, 0, 0, 1);
        }

        #Red_Button_f {
            position: absolute;
            width: 300px;
            height: 42px;
            left: 554px;
            top: 1333.434px;
            overflow: visible;
        }

        #Rectangle_26_f {
            fill: transparent;
            stroke: rgba(0, 0, 0, 1);
            stroke-width: 1px;
            stroke-linejoin: miter;
            stroke-linecap: butt;
            stroke-miterlimit: 4;
            shape-rendering: auto;
        }

        .Rectangle_26_f {
            position: absolute;
            overflow: visible;
            width: 300px;
            height: 42px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1_f {
            fill: rgba(0, 0, 0, 1);
        }

        .Polygon_1_f {
            overflow: visible;
            position: absolute;
            width: 24px;
            height: 11px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 264.5, 15.5) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #VIEW_OUR_PRODUCTS {
            left: 10px;
            top: 10px;
            position: absolute;
            overflow: visible;
            width: 254px;
            height: 31px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(0, 0, 0, 1);
            letter-spacing: 0.2px;
        }

        #The_right_product_for_the_job {
            left: 335px;
            top: 1230.434px;
            position: absolute;
            overflow: visible;
            width: 732px;
            height: 40px;
            line-height: 45px;
            margin-top: -2.5px;
            text-align: center;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: bold;
            font-size: 40px;
            color: rgba(0, 0, 0, 1);
        }

        #Group_263 {
            position: absolute;
            width: 90.01px;
            height: 33px;
            left: 69px;
            top: 1416.434px;
            overflow: visible;
        }

        #Rectangle_27_f {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_f {
            position: absolute;
            overflow: visible;
            width: 90px;
            height: 33px;
            left: 0px;
            top: 0px;
        }

        #O-RINGS {
            left: 5.01px;
            top: 7.744px;
            position: absolute;
            overflow: visible;
            width: 86px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_gb {
            position: absolute;
            width: 185.201px;
            height: 31.354px;
            left: 388.64px;
            top: 1417.215px;
            overflow: visible;
        }

        #Rectangle_27_gc {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_gc {
            position: absolute;
            overflow: visible;
            width: 172.497px;
            height: 31.354px;
            left: 0px;
            top: 0px;
        }

        #MACHINED_METAL {
            left: 6.201px;
            top: 6.963px;
            position: absolute;
            overflow: visible;
            width: 180px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_ge {
            position: absolute;
            width: 238.201px;
            height: 31.354px;
            left: 1350.382px;
            top: 1417.215px;
            overflow: visible;
        }

        #Rectangle_27_gf {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_gf {
            position: absolute;
            overflow: visible;
            width: 238.201px;
            height: 31.354px;
            left: 0px;
            top: 0px;
        }

        #PRODUCTION_EQUIPMENT {
            left: 6.201px;
            top: 6.963px;
            position: absolute;
            overflow: visible;
            width: 233px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_gh {
            position: absolute;
            width: 185.201px;
            height: 31.354px;
            left: 718.926px;
            top: 1415.817px;
            overflow: visible;
        }

        #Rectangle_27_gi {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_gi {
            width: 177.225px;
            height: 31.354px;
            position: absolute;
            overflow: visible;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 0, 0) rotate(0deg);
            transform-origin: center;
        }

        #MOULDED_RUBBER {
            left: 6.201px;
            top: 6.964px;
            position: absolute;
            overflow: visible;
            width: 180px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #GREY_HEADER_gk {
            position: absolute;
            width: 263.201px;
            height: 31.354px;
            left: 1020.096px;
            top: 1415.817px;
            overflow: visible;
        }

        #Rectangle_27_gl {
            fill: rgba(50, 50, 51, 1);
        }

        .Rectangle_27_gl {
            width: 263.201px;
            height: 31.354px;
            position: absolute;
            overflow: visible;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 0, 0) rotate(0deg);
            transform-origin: center;
        }

        #MACHINED_THERMOPLASTICS {
            left: 6.201px;
            top: 6.964px;
            position: absolute;
            overflow: visible;
            width: 258px;
            height: 20px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 20px;
            color: rgba(255, 255, 255, 1);
        }

        #Group_266 {
            position: absolute;
            width: 282.238px;
            height: 279.141px;
            left: 67.812px;
            top: 1449.22px;
            overflow: visible;
        }

        #Group_265 {
            position: absolute;
            width: 282.238px;
            height: 279.141px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_270 {
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 388.64px;
            top: 1449.22px;
            overflow: visible;
        }

        #Path_214 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_214 {
            overflow: visible;
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_269 {
            position: absolute;
            width: 282.239px;
            height: 245.309px;
            left: 0px;
            top: 33.832px;
            overflow: visible;
        }

        #Group_418 {
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 1350.382px;
            top: 1449.22px;
            overflow: visible;
        }

        #Path_214_gt {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_214_gt {
            overflow: visible;
            position: absolute;
            width: 282.239px;
            height: 279.142px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_269_gu {
            position: absolute;
            width: 282.239px;
            height: 245.309px;
            left: 0px;
            top: 33.832px;
            overflow: visible;
        }

        #Group_273 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 718.926px;
            top: 1446.122px;
            overflow: visible;
        }

        #Group_272 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_276 {
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 1020.096px;
            top: 1447.171px;
            overflow: visible;
        }

        #Path_217 {
            fill: rgba(255, 255, 255, 1);
        }

        .Path_217 {
            overflow: visible;
            position: absolute;
            width: 282.239px;
            height: 282.239px;
            left: 0px;
            top: 0px;
            transform: matrix(1, 0, 0, 1, 0, 0);
        }

        #Group_275 {
            position: absolute;
            width: 282.239px;
            height: 142.297px;
            left: 0px;
            top: 69.971px;
            overflow: visible;
        }

        #Group_417 {
            position: absolute;
            width: 42.986px;
            height: 19.645px;
            left: 679px;
            top: 1757.814px;
            overflow: visible;
        }

        #Group_416 {
            position: absolute;
            width: 42.986px;
            height: 19.645px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_429 {
            position: absolute;
            width: 42.986px;
            height: 19.645px;
            left: 679px;
            top: 3289.098px;
            overflow: visible;
        }

        #Group_416_g {
            position: absolute;
            width: 42.986px;
            height: 19.645px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Red_Button_g {
            position: absolute;
            width: 277px;
            height: 57px;
            left: 68px;
            top: 3188px;
            overflow: visible;
        }

        #Rectangle_26_g {
            fill: rgba(221, 20, 22, 1);
        }

        .Rectangle_26_g {
            position: absolute;
            overflow: visible;
            width: 277px;
            height: 57px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1_g {
            fill: rgba(255, 255, 255, 1);
        }

        .Polygon_1_g {
            overflow: visible;
            position: absolute;
            width: 34px;
            height: 17px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 210.5, 20.5) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #FIND_OUT_MORE {
            left: 26px;
            top: 19px;
            position: absolute;
            overflow: visible;
            width: 197px;
            height: 27px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(255, 255, 255, 1);
            letter-spacing: 0.2px;
        }

        #Red_Button_ha {
            position: absolute;
            width: 277px;
            height: 57px;
            left: 722px;
            top: 3188px;
            overflow: visible;
        }

        #Rectangle_26_ha {
            fill: rgba(221, 20, 22, 1);
        }

        .Rectangle_26_ha {
            position: absolute;
            overflow: visible;
            width: 277px;
            height: 57px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1_ha {
            fill: rgba(255, 255, 255, 1);
        }

        .Polygon_1_ha {
            overflow: visible;
            position: absolute;
            width: 34px;
            height: 17px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 210.5, 20.5) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #FIND_OUT_MORE_hb {
            left: 26px;
            top: 19px;
            position: absolute;
            overflow: visible;
            width: 197px;
            height: 27px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(255, 255, 255, 1);
            letter-spacing: 0.2px;
        }

        #Red_Button_hc {
            position: absolute;
            width: 277px;
            height: 57px;
            left: 1349px;
            top: 3188px;
            overflow: visible;
        }

        #Rectangle_26_hd {
            fill: rgba(221, 20, 22, 1);
        }

        .Rectangle_26_hd {
            position: absolute;
            overflow: visible;
            width: 277px;
            height: 57px;
            left: 0px;
            top: 0px;
        }

        #Polygon_1_he {
            fill: rgba(255, 255, 255, 1);
        }

        .Polygon_1_he {
            overflow: visible;
            position: absolute;
            width: 34px;
            height: 17px;
            transform: translate(0px, 0px) matrix(1, 0, 0, 1, 210.5, 20.5) rotate(90deg);
            transform-origin: center;
            left: 0px;
            top: 0px;
        }

        #FIND_OUT_MORE_hf {
            left: 26px;
            top: 19px;
            position: absolute;
            overflow: visible;
            width: 197px;
            height: 27px;
            text-align: left;
            font-family: Myriad Pro;
            font-style: normal;
            font-weight: normal;
            font-size: 25px;
            color: rgba(255, 255, 255, 1);
            letter-spacing: 0.2px;
        }

        #Group_422 {
            position: absolute;
            width: 607.984px;
            height: 278.546px;
            left: 68px;
            top: 2638.501px;
            overflow: visible;
        }

        #Group_421 {
            position: absolute;
            width: 607.984px;
            height: 278.546px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_425 {
            position: absolute;
            width: 585.211px;
            height: 278.545px;
            left: 717.124px;
            top: 2637.103px;
            overflow: visible;
        }

        #Group_424 {
            position: absolute;
            width: 585.211px;
            height: 278.545px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }

        #Group_428 {
            position: absolute;
            width: 607.984px;
            height: 278.546px;
            left: 1349px;
            top: 2638.293px;
            overflow: visible;
        }

        #Group_427 {
            position: absolute;
            width: 607.984px;
            height: 278.546px;
            left: 0px;
            top: 0px;
            overflow: visible;
        }
    </style>
    <script id="applicationScript">
        ///////////////////////////////////////
        // INITIALIZATION
        ///////////////////////////////////////

        /**
         * Functionality for scaling, showing by media query, and navigation between multiple pages on a single page.
         * Code subject to change.
         **/

        if (window.console == null) {
            window["console"] = {
                log: function () {
                }
            }
        }
        ; // some browsers do not set console

        var Application = function () {
            // event constants
            this.prefix = "--web-";
            this.NAVIGATION_CHANGE = "viewChange";
            this.VIEW_NOT_FOUND = "viewNotFound";
            this.VIEW_CHANGE = "viewChange";
            this.VIEW_CHANGING = "viewChanging";
            this.STATE_NOT_FOUND = "stateNotFound";
            this.APPLICATION_COMPLETE = "applicationComplete";
            this.APPLICATION_RESIZE = "applicationResize";
            this.SIZE_STATE_NAME = "data-is-view-scaled";
            this.STATE_NAME = this.prefix + "state";

            this.lastTrigger = null;
            this.lastView = null;
            this.lastState = null;
            this.lastOverlay = null;
            this.currentView = null;
            this.currentState = null;
            this.currentOverlay = null;
            this.currentQuery = {index: 0, rule: null, mediaText: null, id: null};
            this.inclusionQuery = "(min-width: 0px)";
            this.exclusionQuery = "none and (min-width: 99999px)";
            this.LastModifiedDateLabelName = "LastModifiedDateLabel";
            this.viewScaleSliderId = "ViewScaleSliderInput";
            this.pageRefreshedName = "showPageRefreshedNotification";
            this.application = null;
            this.applicationStylesheet = null;
            this.showByMediaQuery = null;
            this.mediaQueryDictionary = {};
            this.viewsDictionary = {};
            this.addedViews = [];
            this.viewStates = [];
            this.views = [];
            this.viewIds = [];
            this.viewQueries = {};
            this.overlays = {};
            this.overlayIds = [];
            this.numberOfViews = 0;
            this.verticalPadding = 0;
            this.horizontalPadding = 0;
            this.stateName = null;
            this.viewScale = 1;
            this.viewLeft = 0;
            this.viewTop = 0;
            this.horizontalScrollbarsNeeded = false;
            this.verticalScrollbarsNeeded = false;

            // view settings
            this.showUpdateNotification = false;
            this.showNavigationControls = false;
            this.scaleViewsToFit = false;
            this.scaleToFitOnDoubleClick = false;
            this.actualSizeOnDoubleClick = false;
            this.scaleViewsOnResize = false;
            this.navigationOnKeypress = false;
            this.showViewName = false;
            this.enableDeepLinking = true;
            this.refreshPageForChanges = false;
            this.showRefreshNotifications = true;

            // view controls
            this.scaleViewSlider = null;
            this.lastModifiedLabel = null;
            this.supportsPopState = false; // window.history.pushState!=null;
            this.initialized = false;

            // refresh properties
            this.refreshDuration = 250;
            this.lastModifiedDate = null;
            this.refreshRequest = null;
            this.refreshInterval = null;
            this.refreshContent = null;
            this.refreshContentSize = null;
            this.refreshCheckContent = false;
            this.refreshCheckContentSize = false;

            var self = this;

            self.initialize = function (event) {
                var view = self.getVisibleView();
                var views = self.getVisibleViews();
                if (view == null) view = self.getInitialView();
                self.collectViews();
                self.collectOverlays();
                self.collectMediaQueries();

                for (let index = 0; index < views.length; index++) {
                    var view = views[index];
                    self.setViewOptions(view);
                    self.setViewVariables(view);
                    self.centerView(view);
                }

                // sometimes the body size is 0 so we call this now and again later
                if (self.initialized) {
                    window.addEventListener(self.NAVIGATION_CHANGE, self.viewChangeHandler);
                    window.addEventListener("keyup", self.keypressHandler);
                    window.addEventListener("keypress", self.keypressHandler);
                    window.addEventListener("resize", self.resizeHandler);
                    window.document.addEventListener("dblclick", self.doubleClickHandler);

                    if (self.supportsPopState) {
                        window.addEventListener('popstate', self.popStateHandler);
                    } else {
                        window.addEventListener('hashchange', self.hashChangeHandler);
                    }

                    // we are ready to go
                    window.dispatchEvent(new Event(self.APPLICATION_COMPLETE));
                }

                if (self.initialized == false) {
                    if (self.enableDeepLinking) {
                        self.syncronizeViewToURL();
                    }

                    if (self.refreshPageForChanges) {
                        self.setupRefreshForChanges();
                    }

                    self.initialized = true;
                }

                if (self.scaleViewsToFit) {
                    self.viewScale = self.scaleViewToFit(view);

                    if (self.viewScale < 0) {
                        setTimeout(self.scaleViewToFit, 500, view);
                    }
                } else if (view) {
                    self.viewScale = self.getViewScaleValue(view);
                    self.centerView(view);
                    self.updateSliderValue(self.viewScale);
                } else {
                    // no view found
                }

                if (self.showUpdateNotification) {
                    self.showNotification();
                }

                //"addEventListener" in window ? null : window.addEventListener = window.attachEvent;
                //"addEventListener" in document ? null : document.addEventListener = document.attachEvent;
            }


            ///////////////////////////////////////
            // AUTO REFRESH
            ///////////////////////////////////////

            self.setupRefreshForChanges = function () {
                self.refreshRequest = new XMLHttpRequest();

                if (!self.refreshRequest) {
                    return false;
                }

                // get document start values immediately
                self.requestRefreshUpdate();
            }

            /**
             * Attempt to check the last modified date by the headers
             * or the last modified property from the byte array (experimental)
             **/
            self.requestRefreshUpdate = function () {
                var url = document.location.href;
                var protocol = window.location.protocol;
                var method;

                try {

                    if (self.refreshCheckContentSize) {
                        self.refreshRequest.open('HEAD', url, true);
                    } else if (self.refreshCheckContent) {
                        self.refreshContent = document.documentElement.outerHTML;
                        self.refreshRequest.open('GET', url, true);
                        self.refreshRequest.responseType = "text";
                    } else {

                        // get page last modified date for the first call to compare to later
                        if (self.lastModifiedDate == null) {

                            // File system does not send headers in FF so get blob if possible
                            if (protocol == "file:") {
                                self.refreshRequest.open("GET", url, true);
                                self.refreshRequest.responseType = "blob";
                            } else {
                                self.refreshRequest.open("HEAD", url, true);
                                self.refreshRequest.responseType = "blob";
                            }

                            self.refreshRequest.onload = self.refreshOnLoadOnceHandler;

                            // In some browsers (Chrome & Safari) this error occurs at send:
                            //
                            // Chrome - Access to XMLHttpRequest at 'file:///index.html' from origin 'null'
                            // has been blocked by CORS policy:
                            // Cross origin requests are only supported for protocol schemes:
                            // http, data, chrome, chrome-extension, https.
                            //
                            // Safari - XMLHttpRequest cannot load file:///Users/user/Public/index.html. Cross origin requests are only supported for HTTP.
                            //
                            // Solution is to run a local server, set local permissions or test in another browser
                            self.refreshRequest.send(null);

                            // In MS browsers the following behavior occurs possibly due to an AJAX call to check last modified date:
                            //
                            // DOM7011: The code on this page disabled back and forward caching.

                            // In Brave (Chrome) error when on the server
                            // index.js:221 HEAD https://www.example.com/ net::ERR_INSUFFICIENT_RESOURCES
                            // self.refreshRequest.send(null);

                        } else {
                            self.refreshRequest = new XMLHttpRequest();
                            self.refreshRequest.onreadystatechange = self.refreshHandler;
                            self.refreshRequest.ontimeout = function () {
                                self.log("Couldn't find page to check for updates");
                            }

                            var method;
                            if (protocol == "file:") {
                                method = "GET";
                            } else {
                                method = "HEAD";
                            }

                            //refreshRequest.open('HEAD', url, true);
                            self.refreshRequest.open(method, url, true);
                            self.refreshRequest.responseType = "blob";
                            self.refreshRequest.send(null);
                        }
                    }
                } catch (error) {
                    self.log("Refresh failed for the following reason:")
                    self.log(error);
                }
            }

            self.refreshHandler = function () {
                var contentSize;

                try {

                    if (self.refreshRequest.readyState === XMLHttpRequest.DONE) {

                        if (self.refreshRequest.status === 2 ||
                            self.refreshRequest.status === 200) {
                            var pageChanged = false;

                            self.updateLastModifiedLabel();

                            if (self.refreshCheckContentSize) {
                                var lastModifiedHeader = self.refreshRequest.getResponseHeader("Last-Modified");
                                contentSize = self.refreshRequest.getResponseHeader("Content-Length");
                                //lastModifiedDate = refreshRequest.getResponseHeader("Last-Modified");
                                var headers = self.refreshRequest.getAllResponseHeaders();
                                var hasContentHeader = headers.indexOf("Content-Length") != -1;

                                if (hasContentHeader) {
                                    contentSize = self.refreshRequest.getResponseHeader("Content-Length");

                                    // size has not been set yet
                                    if (self.refreshContentSize == null) {
                                        self.refreshContentSize = contentSize;
                                        // exit and let interval call this method again
                                        return;
                                    }

                                    if (contentSize != self.refreshContentSize) {
                                        pageChanged = true;
                                    }
                                }
                            } else if (self.refreshCheckContent) {

                                if (self.refreshRequest.responseText != self.refreshContent) {
                                    pageChanged = true;
                                }
                            } else {
                                lastModifiedHeader = self.getLastModified(self.refreshRequest);

                                if (self.lastModifiedDate != lastModifiedHeader) {
                                    self.log("lastModifiedDate:" + self.lastModifiedDate + ",lastModifiedHeader:" + lastModifiedHeader);
                                    pageChanged = true;
                                }

                            }


                            if (pageChanged) {
                                clearInterval(self.refreshInterval);
                                self.refreshUpdatedPage();
                                return;
                            }

                        } else {
                            self.log('There was a problem with the request.');
                        }

                    }
                } catch (error) {
                    //console.log('Caught Exception: ' + error);
                }
            }

            self.refreshOnLoadOnceHandler = function (event) {

                // get the last modified date
                if (self.refreshRequest.response) {
                    self.lastModifiedDate = self.getLastModified(self.refreshRequest);

                    if (self.lastModifiedDate != null) {

                        if (self.refreshInterval == null) {
                            self.refreshInterval = setInterval(self.requestRefreshUpdate, self.refreshDuration);
                        }
                    } else {
                        self.log("Could not get last modified date from the server");
                    }
                }
            }

            self.refreshUpdatedPage = function () {
                if (self.showRefreshNotifications) {
                    var date = new Date().setTime((new Date().getTime() + 10000));
                    document.cookie = encodeURIComponent(self.pageRefreshedName) + "=true" + "; max-age=6000;" + " path=/";
                }

                document.location.reload(true);
            }

            self.showNotification = function (duration) {
                var notificationID = self.pageRefreshedName + "ID";
                var notification = document.getElementById(notificationID);
                if (duration == null) duration = 4000;

                if (notification != null) {
                    return;
                }

                notification = document.createElement("div");
                notification.id = notificationID;
                notification.textContent = "PAGE UPDATED";
                var styleRule = ""
                styleRule = "position: fixed; padding: 7px 16px 6px 16px; font-family: Arial, sans-serif; font-size: 10px; font-weight: bold; left: 50%;";
                styleRule += "top: 20px; background-color: rgba(0,0,0,.5); border-radius: 12px; color:rgb(235, 235, 235); transition: all 2s linear;";
                styleRule += "transform: translateX(-50%); letter-spacing: .5px; filter: drop-shadow(2px 2px 6px rgba(0, 0, 0, .1)); cursor: pointer";
                notification.setAttribute("style", styleRule);

                notification.className = "PageRefreshedClass";
                notification.addEventListener("click", function () {
                    notification.parentNode.removeChild(notification);
                });

                document.body.appendChild(notification);

                setTimeout(function () {
                    notification.style.opacity = "0";
                    notification.style.filter = "drop-shadow( 0px 0px 0px rgba(0,0,0, .5))";
                    setTimeout(function () {
                        try {
                            notification.parentNode.removeChild(notification);
                        } catch (error) {
                        }
                    }, duration)
                }, duration);

                document.cookie = encodeURIComponent(self.pageRefreshedName) + "=; max-age=1; path=/";
            }

            /**
             * Get the last modified date from the header
             * or file object after request has been received
             **/
            self.getLastModified = function (request) {
                var date;

                // file protocol - FILE object with last modified property
                if (request.response && request.response.lastModified) {
                    date = request.response.lastModified;
                }

                // http protocol - check headers
                if (date == null) {
                    date = request.getResponseHeader("Last-Modified");
                }

                return date;
            }

            self.updateLastModifiedLabel = function () {
                var labelValue = "";

                if (self.lastModifiedLabel == null) {
                    self.lastModifiedLabel = document.getElementById("LastModifiedLabel");
                }

                if (self.lastModifiedLabel) {
                    var seconds = parseInt(((new Date().getTime() - Date.parse(document.lastModified)) / 1000 / 60) * 100 + "");
                    var minutes = 0;
                    var hours = 0;

                    if (seconds < 60) {
                        seconds = Math.floor(seconds / 10) * 10;
                        labelValue = seconds + " seconds";
                    } else {
                        minutes = parseInt((seconds / 60) + "");

                        if (minutes > 60) {
                            hours = parseInt((seconds / 60 / 60) + "");
                            labelValue += hours == 1 ? " hour" : " hours";
                        } else {
                            labelValue = minutes + "";
                            labelValue += minutes == 1 ? " minute" : " minutes";
                        }
                    }

                    if (seconds < 10) {
                        labelValue = "Updated now";
                    } else {
                        labelValue = "Updated " + labelValue + " ago";
                    }

                    if (self.lastModifiedLabel.firstElementChild) {
                        self.lastModifiedLabel.firstElementChild.textContent = labelValue;

                    } else if ("textContent" in self.lastModifiedLabel) {
                        self.lastModifiedLabel.textContent = labelValue;
                    }
                }
            }

            self.getShortString = function (string, length) {
                if (length == null) length = 30;
                string = string != null ? string.substr(0, length).replace(/\n/g, "") : "[String is null]";
                return string;
            }

            self.getShortNumber = function (value, places) {
                if (places == null || places < 1) places = 4;
                value = Math.round(value * Math.pow(10, places)) / Math.pow(10, places);
                return value;
            }

            ///////////////////////////////////////
            // NAVIGATION CONTROLS
            ///////////////////////////////////////

            self.updateViewLabel = function () {
                var viewNavigationLabel = document.getElementById("ViewNavigationLabel");
                var view = self.getVisibleView();
                var viewIndex = view ? self.getViewIndex(view) : -1;
                var viewName = view ? self.getViewPreferenceValue(view, self.prefix + "view-name") : null;
                var viewId = view ? view.id : null;

                if (viewNavigationLabel && view) {
                    if (viewName && viewName.indexOf('"') != -1) {
                        viewName = viewName.replace(/"/g, "");
                    }

                    if (self.showViewName) {
                        viewNavigationLabel.textContent = viewName;
                        self.setTooltip(viewNavigationLabel, viewIndex + 1 + " of " + self.numberOfViews);
                    } else {
                        viewNavigationLabel.textContent = viewIndex + 1 + " of " + self.numberOfViews;
                        self.setTooltip(viewNavigationLabel, viewName);
                    }

                }
            }

            self.updateURL = function (view) {
                view = view == null ? self.getVisibleView() : view;
                var viewId = view ? view.id : null
                var viewFragment = view ? "#" + viewId : null;

                if (viewId && self.viewIds.length > 1 && self.enableDeepLinking) {

                    if (self.supportsPopState == false) {
                        self.setFragment(viewId);
                    } else {
                        if (viewFragment != window.location.hash) {

                            if (window.location.hash == null) {
                                window.history.replaceState({name: viewId}, null, viewFragment);
                            } else {
                                window.history.pushState({name: viewId}, null, viewFragment);
                            }
                        }
                    }
                }
            }

            self.updateURLState = function (view, stateName) {
                stateName = view && (stateName == "" || stateName == null) ? self.getStateNameByViewId(view.id) : stateName;

                if (self.supportsPopState == false) {
                    self.setFragment(stateName);
                } else {
                    if (stateName != window.location.hash) {

                        if (window.location.hash == null) {
                            window.history.replaceState({name: view.viewId}, null, stateName);
                        } else {
                            window.history.pushState({name: view.viewId}, null, stateName);
                        }
                    }
                }
            }

            self.setFragment = function (value) {
                window.location.hash = "#" + value;
            }

            self.setTooltip = function (element, value) {
                // setting the tooltip in edge causes a page crash on hover
                if (/Edge/.test(navigator.userAgent)) {
                    return;
                }

                if ("title" in element) {
                    element.title = value;
                }
            }

            self.getStylesheetRules = function (styleSheet) {
                try {
                    if (styleSheet) return styleSheet.cssRules || styleSheet.rules;

                    return document.styleSheets[0]["cssRules"] || document.styleSheets[0]["rules"];
                } catch (error) {
                    // ERRORS:
                    // SecurityError: The operation is insecure.
                    // Errors happen when script loads before stylesheet or loading an external css locally

                    // InvalidAccessError: A parameter or an operation is not supported by the underlying object
                    // Place script after stylesheet

                    console.log(error);
                    if (error.toString().indexOf("The operation is insecure") != -1) {
                        console.log("Load the stylesheet before the script or load the stylesheet inline until it can be loaded on a server")
                    }
                    return [];
                }
            }

            /**
             * If single page application hide all of the views.
             * @param {Number} selectedIndex if provided shows the view at index provided
             **/
            self.hideViews = function (selectedIndex, animation) {
                var rules = self.getStylesheetRules();
                var queryIndex = 0;
                var numberOfRules = rules != null ? rules.length : 0;

                // loop through rules and hide media queries except selected
                for (var i = 0; i < numberOfRules; i++) {
                    var rule = rules[i];
                    var cssText = rule && rule.cssText;

                    if (rule.media != null && cssText.match("--web-view-name:")) {

                        if (queryIndex == selectedIndex) {
                            self.currentQuery.mediaText = rule.conditionText;
                            self.currentQuery.index = selectedIndex;
                            self.currentQuery.rule = rule;
                            self.enableMediaQuery(rule);
                        } else {
                            if (animation) {
                                self.fadeOut(rule)
                            } else {
                                self.disableMediaQuery(rule);
                            }
                        }

                        queryIndex++;
                    }
                }

                self.numberOfViews = queryIndex;
                self.updateViewLabel();
                self.updateURL();

                self.dispatchViewChange();

                var view = self.getVisibleView();
                var viewIndex = view ? self.getViewIndex(view) : -1;

                return viewIndex == selectedIndex ? view : null;
            }

            /**
             * If single page application hide all of the views.
             * @param {HTMLElement} selectedView if provided shows the view passed in
             **/
            self.hideAllViews = function (selectedView, animation) {
                var views = self.views;
                var queryIndex = 0;
                var numberOfViews = views != null ? views.length : 0;

                // loop through rules and hide media queries except selected
                for (var i = 0; i < numberOfViews; i++) {
                    var viewData = views[i];
                    var view = viewData && viewData.view;
                    var mediaRule = viewData && viewData.mediaRule;

                    if (view == selectedView) {
                        self.currentQuery.mediaText = mediaRule.conditionText;
                        self.currentQuery.index = queryIndex;
                        self.currentQuery.rule = mediaRule;
                        self.enableMediaQuery(mediaRule);
                    } else {
                        if (animation) {
                            self.fadeOut(mediaRule)
                        } else {
                            self.disableMediaQuery(mediaRule);
                        }
                    }

                    queryIndex++;
                }

                self.numberOfViews = queryIndex;
                self.updateViewLabel();
                self.updateURL();
                self.dispatchViewChange();

                var visibleView = self.getVisibleView();

                return visibleView == selectedView ? selectedView : null;
            }

            /**
             * Hide view
             * @param {Object} view element to hide
             **/
            self.hideView = function (view) {
                var rule = view ? self.mediaQueryDictionary[view.id] : null;

                if (rule) {
                    self.disableMediaQuery(rule);
                }
            }

            /**
             * Hide overlay
             * @param {Object} overlay element to hide
             **/
            self.hideOverlay = function (overlay) {
                var rule = overlay ? self.mediaQueryDictionary[overlay.id] : null;

                if (rule) {
                    self.disableMediaQuery(rule);

                    //if (self.showByMediaQuery) {
                    overlay.style.display = "none";
                    //}
                }
            }

            /**
             * Show the view by media query. Does not hide current views
             * Sets view options by default
             * @param {Object} view element to show
             * @param {Boolean} setViewOptions sets view options if null or true
             */
            self.showViewByMediaQuery = function (view, setViewOptions) {
                var id = view ? view.id : null;
                var query = id ? self.mediaQueryDictionary[id] : null;
                var isOverlay = view ? self.isOverlay(view) : false;
                setViewOptions = setViewOptions == null ? true : setViewOptions;

                if (query) {
                    self.enableMediaQuery(query);

                    if (isOverlay && view && setViewOptions) {
                        self.setViewVariables(null, view);
                    } else {
                        if (view && setViewOptions) self.setViewOptions(view);
                        if (view && setViewOptions) self.setViewVariables(view);
                    }
                }
            }

            /**
             * Show the view. Does not hide current views
             */
            self.showView = function (view, setViewOptions) {
                var id = view ? view.id : null;
                var query = id ? self.mediaQueryDictionary[id] : null;
                var display = null;
                setViewOptions = setViewOptions == null ? true : setViewOptions;

                if (query) {
                    self.enableMediaQuery(query);
                    if (view == null) view = self.getVisibleView();
                    if (view && setViewOptions) self.setViewOptions(view);
                } else if (id) {
                    display = window.getComputedStyle(view).getPropertyValue("display");
                    if (display == "" || display == "none") {
                        view.style.display = "block";
                    }
                }

                if (view) {
                    if (self.currentView != null) {
                        self.lastView = self.currentView;
                    }

                    self.currentView = view;
                }
            }

            self.showViewById = function (id, setViewOptions) {
                var view = id ? self.getViewById(id) : null;

                if (view) {
                    self.showView(view);
                    return;
                }

                self.log("View not found '" + id + "'");
            }

            self.getElementView = function (element) {
                var view = element;
                var viewFound = false;

                while (viewFound == false || view == null) {
                    if (view && self.viewsDictionary[view.id]) {
                        return view;
                    }
                    view = view.parentNode;
                }
            }

            /**
             * Show overlay over view
             * @param {Event | HTMLElement} event event or html element with styles applied
             * @param {String} id id of view or view reference
             * @param {Number} x x location
             * @param {Number} y y location
             */
            self.showOverlay = function (event, id, x, y) {
                var overlay = id && typeof id === 'string' ? self.getViewById(id) : id ? id : null;
                var query = overlay ? self.mediaQueryDictionary[overlay.id] : null;
                var centerHorizontally = false;
                var centerVertically = false;
                var anchorLeft = false;
                var anchorTop = false;
                var anchorRight = false;
                var anchorBottom = false;
                var display = null;
                var reparent = true;
                var view = null;

                if (overlay == null || overlay == false) {
                    self.log("Overlay not found, '" + id + "'");
                    return;
                }

                // get enter animation - event target must have css variables declared
                if (event) {
                    var button = event.currentTarget || event; // can be event or htmlelement
                    var buttonComputedStyles = getComputedStyle(button);
                    var actionTargetValue = buttonComputedStyles.getPropertyValue(self.prefix + "action-target").trim();
                    var animation = buttonComputedStyles.getPropertyValue(self.prefix + "animation").trim();
                    var isAnimated = animation != "";
                    var targetType = buttonComputedStyles.getPropertyValue(self.prefix + "action-type").trim();
                    var actionTarget = self.application ? null : self.getElement(actionTargetValue);
                    var actionTargetStyles = actionTarget ? actionTarget.style : null;

                    if (actionTargetStyles) {
                        actionTargetStyles.setProperty("animation", animation);
                    }

                    if ("stopImmediatePropagation" in event) {
                        event.stopImmediatePropagation();
                    }
                }

                if (self.application == false || targetType == "page") {
                    document.location.href = "./" + actionTargetValue;
                    return;
                }

                // remove any current overlays
                if (self.currentOverlay) {

                    // act as switch if same button
                    if (self.currentOverlay == actionTarget || self.currentOverlay == null) {
                        if (self.lastTrigger == button) {
                            self.removeOverlay(isAnimated);
                            return;
                        }
                    } else {
                        self.removeOverlay(isAnimated);
                    }
                }

                if (reparent) {
                    view = self.getElementView(button);
                    if (view) {
                        view.appendChild(overlay);
                    }
                }

                if (query) {
                    //self.setElementAnimation(overlay, null);
                    //overlay.style.animation = animation;
                    self.enableMediaQuery(query);

                    var display = overlay && overlay.style.display;

                    if (overlay && display == "" || display == "none") {
                        overlay.style.display = "block";
                        //self.setViewOptions(overlay);
                    }

                    // add animation defined in event target style declaration
                    if (animation && self.supportAnimations) {
                        self.fadeIn(overlay, false, animation);
                    }
                } else if (id) {

                    display = window.getComputedStyle(overlay).getPropertyValue("display");

                    if (display == "" || display == "none") {
                        overlay.style.display = "block";
                    }

                    // add animation defined in event target style declaration
                    if (animation && self.supportAnimations) {
                        self.fadeIn(overlay, false, animation);
                    }
                }

                // do not set x or y position if centering
                var horizontal = self.prefix + "center-horizontally";
                var vertical = self.prefix + "center-vertically";
                var style = overlay.style;
                var transform = [];

                centerHorizontally = self.getIsStyleDefined(id, horizontal) ? self.getViewPreferenceBoolean(overlay, horizontal) : false;
                centerVertically = self.getIsStyleDefined(id, vertical) ? self.getViewPreferenceBoolean(overlay, vertical) : false;
                anchorLeft = self.getIsStyleDefined(id, "left");
                anchorRight = self.getIsStyleDefined(id, "right");
                anchorTop = self.getIsStyleDefined(id, "top");
                anchorBottom = self.getIsStyleDefined(id, "bottom");


                if (self.viewsDictionary[overlay.id] && self.viewsDictionary[overlay.id].styleDeclaration) {
                    style = self.viewsDictionary[overlay.id].styleDeclaration.style;
                }

                if (centerHorizontally) {
                    style.left = "50%";
                    style.transformOrigin = "0 0";
                    transform.push("translateX(-50%)");
                } else if (anchorRight && anchorLeft) {
                    style.left = x + "px";
                } else if (anchorRight) {
                    //style.right = x + "px";
                } else {
                    style.left = x + "px";
                }

                if (centerVertically) {
                    style.top = "50%";
                    transform.push("translateY(-50%)");
                    style.transformOrigin = "0 0";
                } else if (anchorTop && anchorBottom) {
                    style.top = y + "px";
                } else if (anchorBottom) {
                    //style.bottom = y + "px";
                } else {
                    style.top = y + "px";
                }

                if (transform.length) {
                    style.transform = transform.join(" ");
                }

                self.currentOverlay = overlay;
                self.lastTrigger = button;
            }

            self.goBack = function () {
                if (self.currentOverlay) {
                    self.removeOverlay();
                } else if (self.lastView) {
                    self.goToView(self.lastView.id);
                }
            }

            self.removeOverlay = function (animate) {
                var overlay = self.currentOverlay;
                animate = animate === false ? false : true;

                if (overlay) {
                    var style = overlay.style;

                    if (style.animation && self.supportAnimations && animate) {
                        self.reverseAnimation(overlay, true);

                        var duration = self.getAnimationDuration(style.animation, true);

                        setTimeout(function () {
                            self.setElementAnimation(overlay, null);
                            self.hideOverlay(overlay);
                            self.currentOverlay = null;
                        }, duration);
                    } else {
                        self.setElementAnimation(overlay, null);
                        self.hideOverlay(overlay);
                        self.currentOverlay = null;
                    }
                }
            }

            /**
             * Reverse the animation and hide after
             * @param {Object} target element with animation
             * @param {Boolean} hide hide after animation ends
             */
            self.reverseAnimation = function (target, hide) {
                var lastAnimation = null;
                var style = target.style;

                style.animationPlayState = "paused";
                lastAnimation = style.animation;
                style.animation = null;
                style.animationPlayState = "paused";

                if (hide) {
                    //target.addEventListener("animationend", self.animationEndHideHandler);

                    var duration = self.getAnimationDuration(lastAnimation, true);
                    var isOverlay = self.isOverlay(target);

                    setTimeout(function () {
                        self.setElementAnimation(target, null);

                        if (isOverlay) {
                            self.hideOverlay(target);
                        } else {
                            self.hideView(target);
                        }
                    }, duration);
                }

                setTimeout(function () {
                    style.animation = lastAnimation;
                    style.animationPlayState = "paused";
                    style.animationDirection = "reverse";
                    style.animationPlayState = "running";
                }, 30);
            }

            self.animationEndHandler = function (event) {
                var target = event.currentTarget;
                self.dispatchEvent(new Event(event.type));
            }

            self.isOverlay = function (view) {
                var result = view ? self.getViewPreferenceBoolean(view, self.prefix + "is-overlay") : false;

                return result;
            }

            self.animationEndHideHandler = function (event) {
                var target = event.currentTarget;
                self.setViewVariables(null, target);
                self.hideView(target);
                target.removeEventListener("animationend", self.animationEndHideHandler);
            }

            self.animationEndShowHandler = function (event) {
                var target = event.currentTarget;
                target.removeEventListener("animationend", self.animationEndShowHandler);
            }

            self.setViewOptions = function (view) {

                if (view) {
                    self.minimumScale = self.getViewPreferenceValue(view, self.prefix + "minimum-scale");
                    self.maximumScale = self.getViewPreferenceValue(view, self.prefix + "maximum-scale");
                    self.scaleViewsToFit = self.getViewPreferenceBoolean(view, self.prefix + "scale-to-fit");
                    self.scaleToFitType = self.getViewPreferenceValue(view, self.prefix + "scale-to-fit-type");
                    self.scaleToFitOnDoubleClick = self.getViewPreferenceBoolean(view, self.prefix + "scale-on-double-click");
                    self.actualSizeOnDoubleClick = self.getViewPreferenceBoolean(view, self.prefix + "actual-size-on-double-click");
                    self.scaleViewsOnResize = self.getViewPreferenceBoolean(view, self.prefix + "scale-on-resize");
                    self.enableScaleUp = self.getViewPreferenceBoolean(view, self.prefix + "enable-scale-up");
                    self.centerHorizontally = self.getViewPreferenceBoolean(view, self.prefix + "center-horizontally");
                    self.centerVertically = self.getViewPreferenceBoolean(view, self.prefix + "center-vertically");
                    self.navigationOnKeypress = self.getViewPreferenceBoolean(view, self.prefix + "navigate-on-keypress");
                    self.showViewName = self.getViewPreferenceBoolean(view, self.prefix + "show-view-name");
                    self.refreshPageForChanges = self.getViewPreferenceBoolean(view, self.prefix + "refresh-for-changes");
                    self.refreshPageForChangesInterval = self.getViewPreferenceValue(view, self.prefix + "refresh-interval");
                    self.showNavigationControls = self.getViewPreferenceBoolean(view, self.prefix + "show-navigation-controls");
                    self.scaleViewSlider = self.getViewPreferenceBoolean(view, self.prefix + "show-scale-controls");
                    self.enableDeepLinking = self.getViewPreferenceBoolean(view, self.prefix + "enable-deep-linking");
                    self.singlePageApplication = self.getViewPreferenceBoolean(view, self.prefix + "application");
                    self.showByMediaQuery = self.getViewPreferenceBoolean(view, self.prefix + "show-by-media-query");
                    self.showUpdateNotification = document.cookie != "" ? document.cookie.indexOf(self.pageRefreshedName) != -1 : false;
                    self.imageComparisonDuration = self.getViewPreferenceValue(view, self.prefix + "image-comparison-duration");
                    self.supportAnimations = self.getViewPreferenceBoolean(view, self.prefix + "enable-animations", true);

                    if (self.scaleViewsToFit) {
                        var newScaleValue = self.scaleViewToFit(view);

                        if (newScaleValue < 0) {
                            setTimeout(self.scaleViewToFit, 500, view);
                        }
                    } else {
                        self.viewScale = self.getViewScaleValue(view);
                        self.viewToFitWidthScale = self.getViewFitToViewportWidthScale(view, self.enableScaleUp)
                        self.viewToFitHeightScale = self.getViewFitToViewportScale(view, self.enableScaleUp);
                        self.updateSliderValue(self.viewScale);
                    }

                    if (self.imageComparisonDuration != null) {
                        // todo
                    }

                    if (self.refreshPageForChangesInterval != null) {
                        self.refreshDuration = Number(self.refreshPageForChangesInterval);
                    }
                }
            }

            self.previousView = function (event) {
                var rules = self.getStylesheetRules();
                var view = self.getVisibleView()
                var index = view ? self.getViewIndex(view) : -1;
                var prevQueryIndex = index != -1 ? index - 1 : self.currentQuery.index - 1;
                var queryIndex = 0;
                var numberOfRules = rules != null ? rules.length : 0;

                if (event) {
                    event.stopImmediatePropagation();
                }

                if (prevQueryIndex < 0) {
                    return;
                }

                // loop through rules and hide media queries except selected
                for (var i = 0; i < numberOfRules; i++) {
                    var rule = rules[i];

                    if (rule.media != null) {

                        if (queryIndex == prevQueryIndex) {
                            self.currentQuery.mediaText = rule.conditionText;
                            self.currentQuery.index = prevQueryIndex;
                            self.currentQuery.rule = rule;
                            self.enableMediaQuery(rule);
                            self.updateViewLabel();
                            self.updateURL();
                            self.dispatchViewChange();
                        } else {
                            self.disableMediaQuery(rule);
                        }

                        queryIndex++;
                    }
                }
            }

            self.nextView = function (event) {
                var rules = self.getStylesheetRules();
                var view = self.getVisibleView();
                var index = view ? self.getViewIndex(view) : -1;
                var nextQueryIndex = index != -1 ? index + 1 : self.currentQuery.index + 1;
                var queryIndex = 0;
                var numberOfRules = rules != null ? rules.length : 0;
                var numberOfMediaQueries = self.getNumberOfMediaRules();

                if (event) {
                    event.stopImmediatePropagation();
                }

                if (nextQueryIndex >= numberOfMediaQueries) {
                    return;
                }

                // loop through rules and hide media queries except selected
                for (var i = 0; i < numberOfRules; i++) {
                    var rule = rules[i];

                    if (rule.media != null) {

                        if (queryIndex == nextQueryIndex) {
                            self.currentQuery.mediaText = rule.conditionText;
                            self.currentQuery.index = nextQueryIndex;
                            self.currentQuery.rule = rule;
                            self.enableMediaQuery(rule);
                            self.updateViewLabel();
                            self.updateURL();
                            self.dispatchViewChange();
                        } else {
                            self.disableMediaQuery(rule);
                        }

                        queryIndex++;
                    }
                }
            }

            /**
             * Enables a view via media query
             */
            self.enableMediaQuery = function (rule) {

                try {
                    rule.media.mediaText = self.inclusionQuery;
                } catch (error) {
                    //self.log(error);
                    rule.conditionText = self.inclusionQuery;
                }
            }

            self.disableMediaQuery = function (rule) {

                try {
                    rule.media.mediaText = self.exclusionQuery;
                } catch (error) {
                    rule.conditionText = self.exclusionQuery;
                }
            }

            self.dispatchViewChange = function () {
                try {
                    var event = new Event(self.NAVIGATION_CHANGE);
                    window.dispatchEvent(event);
                } catch (error) {
                    // In IE 11: Object doesn't support this action
                }
            }

            self.getNumberOfMediaRules = function () {
                var rules = self.getStylesheetRules();
                var numberOfRules = rules ? rules.length : 0;
                var numberOfQueries = 0;

                for (var i = 0; i < numberOfRules; i++) {
                    if (rules[i].media != null) {
                        numberOfQueries++;
                    }
                }

                return numberOfQueries;
            }

            /////////////////////////////////////////
            // VIEW SCALE
            /////////////////////////////////////////

            self.sliderChangeHandler = function (event) {
                var value = self.getShortNumber(event.currentTarget.value / 100);
                var view = self.getVisibleView();
                self.setViewScaleValue(view, false, value, true);
            }

            self.updateSliderValue = function (scale) {
                var slider = document.getElementById(self.viewScaleSliderId);
                var tooltip = parseInt(scale * 100 + "") + "%";
                var inputType;
                var inputValue;

                if (slider) {
                    inputValue = self.getShortNumber(scale * 100);
                    if (inputValue != slider["value"]) {
                        slider["value"] = inputValue;
                    }
                    inputType = slider.getAttributeNS(null, "type");

                    if (inputType != "range") {
                        // input range is not supported
                        slider.style.display = "none";
                    }

                    self.setTooltip(slider, tooltip);
                }
            }

            self.viewChangeHandler = function (event) {
                var view = self.getVisibleView();
                var matrix = view ? getComputedStyle(view).transform : null;

                if (matrix) {
                    self.viewScale = self.getViewScaleValue(view);

                    var scaleNeededToFit = self.getViewFitToViewportScale(view);
                    var isViewLargerThanViewport = scaleNeededToFit < 1;

                    // scale large view to fit if scale to fit is enabled
                    if (self.scaleViewsToFit) {
                        self.scaleViewToFit(view);
                    } else {
                        self.updateSliderValue(self.viewScale);
                    }
                }
            }

            self.getViewScaleValue = function (view) {
                var matrix = getComputedStyle(view).transform;

                if (matrix) {
                    var matrixArray = matrix.replace("matrix(", "").split(",");
                    var scaleX = parseFloat(matrixArray[0]);
                    var scaleY = parseFloat(matrixArray[3]);
                    var scale = Math.min(scaleX, scaleY);
                }

                return scale;
            }

            /**
             * Scales view to scale.
             * @param {Object} view view to scale. views are in views array
             * @param {Boolean} scaleToFit set to true to scale to fit. set false to use desired scale value
             * @param {Number} desiredScale scale to define. not used if scale to fit is false
             * @param {Boolean} isSliderChange indicates if slider is callee
             */
            self.setViewScaleValue = function (view, scaleToFit, desiredScale, isSliderChange) {
                var enableScaleUp = self.enableScaleUp;
                var scaleToFitType = self.scaleToFitType;
                var minimumScale = self.minimumScale;
                var maximumScale = self.maximumScale;
                var hasMinimumScale = !isNaN(minimumScale) && minimumScale != "";
                var hasMaximumScale = !isNaN(maximumScale) && maximumScale != "";
                var scaleNeededToFit = self.getViewFitToViewportScale(view, enableScaleUp);
                var scaleNeededToFitWidth = self.getViewFitToViewportWidthScale(view, enableScaleUp);
                var scaleNeededToFitHeight = self.getViewFitToViewportHeightScale(view, enableScaleUp);
                var scaleToFitFull = self.getViewFitToViewportScale(view, true);
                var scaleToFitFullWidth = self.getViewFitToViewportWidthScale(view, true);
                var scaleToFitFullHeight = self.getViewFitToViewportHeightScale(view, true);
                var scaleToWidth = scaleToFitType == "width";
                var scaleToHeight = scaleToFitType == "height";
                var shrunkToFit = false;
                var topPosition = null;
                var leftPosition = null;
                var translateY = null;
                var translateX = null;
                var transformValue = "";
                var canCenterVertically = true;
                var canCenterHorizontally = true;
                var style = view.style;

                if (view && self.viewsDictionary[view.id] && self.viewsDictionary[view.id].styleDeclaration) {
                    style = self.viewsDictionary[view.id].styleDeclaration.style;
                }

                if (scaleToFit && isSliderChange != true) {
                    if (scaleToFitType == "fit" || scaleToFitType == "") {
                        desiredScale = scaleNeededToFit;
                    } else if (scaleToFitType == "width") {
                        desiredScale = scaleNeededToFitWidth;
                    } else if (scaleToFitType == "height") {
                        desiredScale = scaleNeededToFitHeight;
                    }
                } else {
                    if (isNaN(desiredScale)) {
                        desiredScale = 1;
                    }
                }

                self.updateSliderValue(desiredScale);

                // scale to fit width
                if (scaleToWidth && scaleToHeight == false) {
                    canCenterVertically = scaleNeededToFitHeight >= scaleNeededToFitWidth;
                    canCenterHorizontally = scaleNeededToFitWidth >= 1 && enableScaleUp == false;

                    if (isSliderChange) {
                        canCenterHorizontally = desiredScale < scaleToFitFullWidth;
                    } else if (scaleToFit) {
                        desiredScale = scaleNeededToFitWidth;
                    }

                    if (hasMinimumScale) {
                        desiredScale = Math.max(desiredScale, Number(minimumScale));
                    }

                    if (hasMaximumScale) {
                        desiredScale = Math.min(desiredScale, Number(maximumScale));
                    }

                    desiredScale = self.getShortNumber(desiredScale);

                    canCenterHorizontally = self.canCenterHorizontally(view, "width", enableScaleUp, desiredScale, minimumScale, maximumScale);
                    canCenterVertically = self.canCenterVertically(view, "width", enableScaleUp, desiredScale, minimumScale, maximumScale);

                    if (desiredScale > 1 && (enableScaleUp || isSliderChange)) {
                        transformValue = "scale(" + desiredScale + ")";
                    } else if (desiredScale >= 1 && enableScaleUp == false) {
                        transformValue = "scale(" + 1 + ")";
                    } else {
                        transformValue = "scale(" + desiredScale + ")";
                    }

                    if (self.centerVertically) {
                        if (canCenterVertically) {
                            translateY = "-50%";
                            topPosition = "50%";
                        } else {
                            translateY = "0";
                            topPosition = "0";
                        }

                        if (style.top != topPosition) {
                            style.top = topPosition + "";
                        }

                        if (canCenterVertically) {
                            transformValue += " translateY(" + translateY + ")";
                        }
                    }

                    if (self.centerHorizontally) {
                        if (canCenterHorizontally) {
                            translateX = "-50%";
                            leftPosition = "50%";
                        } else {
                            translateX = "0";
                            leftPosition = "0";
                        }

                        if (style.left != leftPosition) {
                            style.left = leftPosition + "";
                        }

                        if (canCenterHorizontally) {
                            transformValue += " translateX(" + translateX + ")";
                        }
                    }

                    style.transformOrigin = "0 0";
                    style.transform = transformValue;

                    self.viewScale = desiredScale;
                    self.viewToFitWidthScale = scaleNeededToFitWidth;
                    self.viewToFitHeightScale = scaleNeededToFitHeight;
                    self.viewLeft = leftPosition;
                    self.viewTop = topPosition;

                    return desiredScale;
                }

                // scale to fit height
                if (scaleToHeight && scaleToWidth == false) {
                    //canCenterVertically = scaleNeededToFitHeight>=scaleNeededToFitWidth;
                    //canCenterHorizontally = scaleNeededToFitHeight<=scaleNeededToFitWidth && enableScaleUp==false;
                    canCenterVertically = scaleNeededToFitHeight >= scaleNeededToFitWidth;
                    canCenterHorizontally = scaleNeededToFitWidth >= 1 && enableScaleUp == false;

                    if (isSliderChange) {
                        canCenterHorizontally = desiredScale < scaleToFitFullHeight;
                    } else if (scaleToFit) {
                        desiredScale = scaleNeededToFitHeight;
                    }

                    if (hasMinimumScale) {
                        desiredScale = Math.max(desiredScale, Number(minimumScale));
                    }

                    if (hasMaximumScale) {
                        desiredScale = Math.min(desiredScale, Number(maximumScale));
                        //canCenterVertically = desiredScale>=scaleNeededToFitHeight && enableScaleUp==false;
                    }

                    desiredScale = self.getShortNumber(desiredScale);

                    canCenterHorizontally = self.canCenterHorizontally(view, "height", enableScaleUp, desiredScale, minimumScale, maximumScale);
                    canCenterVertically = self.canCenterVertically(view, "height", enableScaleUp, desiredScale, minimumScale, maximumScale);

                    if (desiredScale > 1 && (enableScaleUp || isSliderChange)) {
                        transformValue = "scale(" + desiredScale + ")";
                    } else if (desiredScale >= 1 && enableScaleUp == false) {
                        transformValue = "scale(" + 1 + ")";
                    } else {
                        transformValue = "scale(" + desiredScale + ")";
                    }

                    if (self.centerHorizontally) {
                        if (canCenterHorizontally) {
                            translateX = "-50%";
                            leftPosition = "50%";
                        } else {
                            translateX = "0";
                            leftPosition = "0";
                        }

                        if (style.left != leftPosition) {
                            style.left = leftPosition + "";
                        }

                        if (canCenterHorizontally) {
                            transformValue += " translateX(" + translateX + ")";
                        }
                    }

                    if (self.centerVertically) {
                        if (canCenterVertically) {
                            translateY = "-50%";
                            topPosition = "50%";
                        } else {
                            translateY = "0";
                            topPosition = "0";
                        }

                        if (style.top != topPosition) {
                            style.top = topPosition + "";
                        }

                        if (canCenterVertically) {
                            transformValue += " translateY(" + translateY + ")";
                        }
                    }

                    style.transformOrigin = "0 0";
                    style.transform = transformValue;

                    self.viewScale = desiredScale;
                    self.viewToFitWidthScale = scaleNeededToFitWidth;
                    self.viewToFitHeightScale = scaleNeededToFitHeight;
                    self.viewLeft = leftPosition;
                    self.viewTop = topPosition;

                    return scaleNeededToFitHeight;
                }

                if (scaleToFitType == "fit") {
                    //canCenterVertically = scaleNeededToFitHeight>=scaleNeededToFitWidth;
                    //canCenterHorizontally = scaleNeededToFitWidth>=scaleNeededToFitHeight;
                    canCenterVertically = scaleNeededToFitHeight >= scaleNeededToFit;
                    canCenterHorizontally = scaleNeededToFitWidth >= scaleNeededToFit;

                    if (hasMinimumScale) {
                        desiredScale = Math.max(desiredScale, Number(minimumScale));
                    }

                    desiredScale = self.getShortNumber(desiredScale);

                    if (isSliderChange || scaleToFit == false) {
                        canCenterVertically = scaleToFitFullHeight >= desiredScale;
                        canCenterHorizontally = desiredScale < scaleToFitFullWidth;
                    } else if (scaleToFit) {
                        desiredScale = scaleNeededToFit;
                    }

                    transformValue = "scale(" + desiredScale + ")";

                    //canCenterHorizontally = self.canCenterHorizontally(view, "fit", false, desiredScale);
                    //canCenterVertically = self.canCenterVertically(view, "fit", false, desiredScale);

                    if (self.centerVertically) {
                        if (canCenterVertically) {
                            translateY = "-50%";
                            topPosition = "50%";
                        } else {
                            translateY = "0";
                            topPosition = "0";
                        }

                        if (style.top != topPosition) {
                            style.top = topPosition + "";
                        }

                        if (canCenterVertically) {
                            transformValue += " translateY(" + translateY + ")";
                        }
                    }

                    if (self.centerHorizontally) {
                        if (canCenterHorizontally) {
                            translateX = "-50%";
                            leftPosition = "50%";
                        } else {
                            translateX = "0";
                            leftPosition = "0";
                        }

                        if (style.left != leftPosition) {
                            style.left = leftPosition + "";
                        }

                        if (canCenterHorizontally) {
                            transformValue += " translateX(" + translateX + ")";
                        }
                    }

                    style.transformOrigin = "0 0";
                    style.transform = transformValue;

                    self.viewScale = desiredScale;
                    self.viewToFitWidthScale = scaleNeededToFitWidth;
                    self.viewToFitHeightScale = scaleNeededToFitHeight;
                    self.viewLeft = leftPosition;
                    self.viewTop = topPosition;

                    self.updateSliderValue(desiredScale);

                    return desiredScale;
                }

                if (scaleToFitType == "default" || scaleToFitType == "") {
                    desiredScale = 1;

                    if (hasMinimumScale) {
                        desiredScale = Math.max(desiredScale, Number(minimumScale));
                    }
                    if (hasMaximumScale) {
                        desiredScale = Math.min(desiredScale, Number(maximumScale));
                    }

                    canCenterHorizontally = self.canCenterHorizontally(view, "none", false, desiredScale, minimumScale, maximumScale);
                    canCenterVertically = self.canCenterVertically(view, "none", false, desiredScale, minimumScale, maximumScale);

                    if (self.centerVertically) {
                        if (canCenterVertically) {
                            translateY = "-50%";
                            topPosition = "50%";
                        } else {
                            translateY = "0";
                            topPosition = "0";
                        }

                        if (style.top != topPosition) {
                            style.top = topPosition + "";
                        }

                        if (canCenterVertically) {
                            transformValue += " translateY(" + translateY + ")";
                        }
                    }

                    if (self.centerHorizontally) {
                        if (canCenterHorizontally) {
                            translateX = "-50%";
                            leftPosition = "50%";
                        } else {
                            translateX = "0";
                            leftPosition = "0";
                        }

                        if (style.left != leftPosition) {
                            style.left = leftPosition + "";
                        }

                        if (canCenterHorizontally) {
                            transformValue += " translateX(" + translateX + ")";
                        } else {
                            transformValue += " translateX(" + 0 + ")";
                        }
                    }

                    style.transformOrigin = "0 0";
                    style.transform = transformValue;


                    self.viewScale = desiredScale;
                    self.viewToFitWidthScale = scaleNeededToFitWidth;
                    self.viewToFitHeightScale = scaleNeededToFitHeight;
                    self.viewLeft = leftPosition;
                    self.viewTop = topPosition;

                    self.updateSliderValue(desiredScale);

                    return desiredScale;
                }
            }

            /**
             * Returns true if view can be centered horizontally
             * @param {HTMLElement} view view
             * @param {String} type type of scaling - width, height, all, none
             * @param {Boolean} scaleUp if scale up enabled
             * @param {Number} scale target scale value
             */
            self.canCenterHorizontally = function (view, type, scaleUp, scale, minimumScale, maximumScale) {
                var scaleNeededToFit = self.getViewFitToViewportScale(view, scaleUp);
                var scaleNeededToFitHeight = self.getViewFitToViewportHeightScale(view, scaleUp);
                var scaleNeededToFitWidth = self.getViewFitToViewportWidthScale(view, scaleUp);
                var canCenter = false;
                var minScale;

                type = type == null ? "none" : type;
                scale = scale == null ? scale : scaleNeededToFitWidth;
                scaleUp = scaleUp == null ? false : scaleUp;

                if (type == "width") {

                    if (scaleUp && maximumScale == null) {
                        canCenter = false;
                    } else if (scaleNeededToFitWidth >= 1) {
                        canCenter = true;
                    }
                } else if (type == "height") {
                    minScale = Math.min(1, scaleNeededToFitHeight);
                    if (minimumScale != "" && maximumScale != "") {
                        minScale = Math.max(minimumScale, Math.min(maximumScale, scaleNeededToFitHeight));
                    } else {
                        if (minimumScale != "") {
                            minScale = Math.max(minimumScale, scaleNeededToFitHeight);
                        }
                        if (maximumScale != "") {
                            minScale = Math.max(minimumScale, Math.min(maximumScale, scaleNeededToFitHeight));
                        }
                    }

                    if (scaleUp && maximumScale == "") {
                        canCenter = false;
                    } else if (scaleNeededToFitWidth >= minScale) {
                        canCenter = true;
                    }
                } else if (type == "fit") {
                    canCenter = scaleNeededToFitWidth >= scaleNeededToFit;
                } else {
                    if (scaleUp) {
                        canCenter = false;
                    } else if (scaleNeededToFitWidth >= 1) {
                        canCenter = true;
                    }
                }

                self.horizontalScrollbarsNeeded = canCenter;

                return canCenter;
            }

            /**
             * Returns true if view can be centered horizontally
             * @param {HTMLElement} view view to scale
             * @param {String} type type of scaling
             * @param {Boolean} scaleUp if scale up enabled
             * @param {Number} scale target scale value
             */
            self.canCenterVertically = function (view, type, scaleUp, scale, minimumScale, maximumScale) {
                var scaleNeededToFit = self.getViewFitToViewportScale(view, scaleUp);
                var scaleNeededToFitWidth = self.getViewFitToViewportWidthScale(view, scaleUp);
                var scaleNeededToFitHeight = self.getViewFitToViewportHeightScale(view, scaleUp);
                var canCenter = false;
                var minScale;

                type = type == null ? "none" : type;
                scale = scale == null ? 1 : scale;
                scaleUp = scaleUp == null ? false : scaleUp;

                if (type == "width") {
                    canCenter = scaleNeededToFitHeight >= scaleNeededToFitWidth;
                } else if (type == "height") {
                    minScale = Math.max(minimumScale, Math.min(maximumScale, scaleNeededToFit));
                    canCenter = scaleNeededToFitHeight >= minScale;
                } else if (type == "fit") {
                    canCenter = scaleNeededToFitHeight >= scaleNeededToFit;
                } else {
                    if (scaleUp) {
                        canCenter = false;
                    } else if (scaleNeededToFitHeight >= 1) {
                        canCenter = true;
                    }
                }

                self.verticalScrollbarsNeeded = canCenter;

                return canCenter;
            }

            self.getViewFitToViewportScale = function (view, scaleUp) {
                var enableScaleUp = scaleUp;
                var availableWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var availableHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                var elementWidth = parseFloat(getComputedStyle(view, "style").width);
                var elementHeight = parseFloat(getComputedStyle(view, "style").height);
                var newScale = 1;

                // if element is not added to the document computed values are NaN
                if (isNaN(elementWidth) || isNaN(elementHeight)) {
                    return newScale;
                }

                availableWidth -= self.horizontalPadding;
                availableHeight -= self.verticalPadding;

                if (enableScaleUp) {
                    newScale = Math.min(availableHeight / elementHeight, availableWidth / elementWidth);
                } else if (elementWidth > availableWidth || elementHeight > availableHeight) {
                    newScale = Math.min(availableHeight / elementHeight, availableWidth / elementWidth);
                }

                return newScale;
            }

            self.getViewFitToViewportWidthScale = function (view, scaleUp) {
                // need to get browser viewport width when element
                var isParentWindow = view && view.parentNode && view.parentNode === document.body;
                var enableScaleUp = scaleUp;
                var availableWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var elementWidth = parseFloat(getComputedStyle(view, "style").width);
                var newScale = 1;

                // if element is not added to the document computed values are NaN
                if (isNaN(elementWidth)) {
                    return newScale;
                }

                availableWidth -= self.horizontalPadding;

                if (enableScaleUp) {
                    newScale = availableWidth / elementWidth;
                } else if (elementWidth > availableWidth) {
                    newScale = availableWidth / elementWidth;
                }

                return newScale;
            }

            self.getViewFitToViewportHeightScale = function (view, scaleUp) {
                var enableScaleUp = scaleUp;
                var availableHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                var elementHeight = parseFloat(getComputedStyle(view, "style").height);
                var newScale = 1;

                // if element is not added to the document computed values are NaN
                if (isNaN(elementHeight)) {
                    return newScale;
                }

                availableHeight -= self.verticalPadding;

                if (enableScaleUp) {
                    newScale = availableHeight / elementHeight;
                } else if (elementHeight > availableHeight) {
                    newScale = availableHeight / elementHeight;
                }

                return newScale;
            }

            self.keypressHandler = function (event) {
                var rightKey = 39;
                var leftKey = 37;

                // listen for both events
                if (event.type == "keypress") {
                    window.removeEventListener("keyup", self.keypressHandler);
                } else {
                    window.removeEventListener("keypress", self.keypressHandler);
                }

                if (self.showNavigationControls) {
                    if (self.navigationOnKeypress) {
                        if (event.keyCode == rightKey) {
                            self.nextView();
                        }
                        if (event.keyCode == leftKey) {
                            self.previousView();
                        }
                    }
                } else if (self.navigationOnKeypress) {
                    if (event.keyCode == rightKey) {
                        self.nextView();
                    }
                    if (event.keyCode == leftKey) {
                        self.previousView();
                    }
                }
            }

            ///////////////////////////////////
            // GENERAL FUNCTIONS
            ///////////////////////////////////

            self.getViewById = function (id) {
                id = id ? id.replace("#", "") : "";
                var view = self.viewIds.indexOf(id) != -1 && self.getElement(id);
                return view;
            }

            self.getViewIds = function () {
                var viewIds = self.getViewPreferenceValue(document.body, self.prefix + "view-ids");
                var viewId = null;

                viewIds = viewIds != null && viewIds != "" ? viewIds.split(",") : [];

                if (viewIds.length == 0) {
                    viewId = self.getViewPreferenceValue(document.body, self.prefix + "view-id");
                    viewIds = viewId ? [viewId] : [];
                }

                return viewIds;
            }

            self.getInitialViewId = function () {
                var viewId = self.getViewPreferenceValue(document.body, self.prefix + "view-id");
                return viewId;
            }

            self.getApplicationStylesheet = function () {
                var stylesheetId = self.getViewPreferenceValue(document.body, self.prefix + "stylesheet-id");
                self.applicationStylesheet = document.getElementById("applicationStylesheet");
                return self.applicationStylesheet.sheet;
            }

            self.getVisibleView = function () {
                var viewIds = self.getViewIds();

                for (var i = 0; i < viewIds.length; i++) {
                    var viewId = viewIds[i].replace(/[\#?\.?](.*)/, "$" + "1");
                    var view = self.getElement(viewId);
                    var postName = "_Class";

                    if (view == null && viewId && viewId.lastIndexOf(postName) != -1) {
                        view = self.getElement(viewId.replace(postName, ""));
                    }

                    if (view) {
                        var display = getComputedStyle(view).display;

                        if (display == "block" || display == "flex") {
                            return view;
                        }
                    }
                }

                return null;
            }

            self.getVisibleViews = function () {
                var viewIds = self.getViewIds();
                var views = [];

                for (var i = 0; i < viewIds.length; i++) {
                    var viewId = viewIds[i].replace(/[\#?\.?](.*)/, "$" + "1");
                    var view = self.getElement(viewId);
                    var postName = "_Class";

                    if (view == null && viewId && viewId.lastIndexOf(postName) != -1) {
                        view = self.getElement(viewId.replace(postName, ""));
                    }

                    if (view) {
                        var display = getComputedStyle(view).display;

                        if (display == "none") {
                            continue;
                        }

                        if (display == "block" || display == "flex") {
                            views.push(view);
                        }
                    }
                }

                return views;
            }

            self.getStateNameByViewId = function (id) {
                var state = self.viewsDictionary[id];
                return state && state.stateName;
            }

            self.getMatchingViews = function (ids) {
                var views = self.addedViews.slice(0);
                var matchingViews = [];

                if (self.showByMediaQuery) {
                    for (let index = 0; index < views.length; index++) {
                        var viewId = views[index];
                        var state = self.viewsDictionary[viewId];
                        var rule = state && state.rule;
                        var matchResults = window.matchMedia(rule.conditionText);
                        var view = self.views[viewId];

                        if (matchResults.matches) {
                            if (ids == true) {
                                matchingViews.push(viewId);
                            } else {
                                matchingViews.push(view);
                            }
                        }
                    }
                }

                return matchingViews;
            }

            self.ruleMatchesQuery = function (rule) {
                var result = window.matchMedia(rule.conditionText);
                return result.matches;
            }

            self.getViewsByStateName = function (stateName, matchQuery) {
                var views = self.addedViews.slice(0);
                var matchingViews = [];

                if (self.showByMediaQuery) {

                    // find state name
                    for (let index = 0; index < views.length; index++) {
                        var viewId = views[index];
                        var state = self.viewsDictionary[viewId];
                        var rule = state.rule;
                        var mediaRule = state.mediaRule;
                        var view = self.views[viewId];
                        var viewStateName = self.getStyleRuleValue(mediaRule, self.STATE_NAME, state);
                        var stateFoundAtt = view.getAttribute(self.STATE_NAME) == state;
                        var matchesResults = false;

                        if (viewStateName == stateName) {
                            if (matchQuery) {
                                matchesResults = self.ruleMatchesQuery(rule);

                                if (matchesResults) {
                                    matchingViews.push(view);
                                }
                            } else {
                                matchingViews.push(view);
                            }
                        }
                    }
                }

                return matchingViews;
            }

            self.getInitialView = function () {
                var viewId = self.getInitialViewId();
                viewId = viewId.replace(/[\#?\.?](.*)/, "$" + "1");
                var view = self.getElement(viewId);
                var postName = "_Class";

                if (view == null && viewId && viewId.lastIndexOf(postName) != -1) {
                    view = self.getElement(viewId.replace(postName, ""));
                }

                return view;
            }

            self.getViewIndex = function (view) {
                var viewIds = self.getViewIds();
                var id = view ? view.id : null;
                var index = id && viewIds ? viewIds.indexOf(id) : -1;

                return index;
            }

            self.syncronizeViewToURL = function () {
                var fragment = self.getHashFragment();

                if (self.showByMediaQuery) {
                    var stateName = fragment;

                    if (stateName == null || stateName == "") {
                        var initialView = self.getInitialView();
                        stateName = initialView ? self.getStateNameByViewId(initialView.id) : null;
                    }

                    self.showMediaQueryViewsByState(stateName);
                    return;
                }

                var view = self.getViewById(fragment);
                var index = view ? self.getViewIndex(view) : 0;
                if (index == -1) index = 0;
                var currentView = self.hideViews(index);

                if (self.supportsPopState && currentView) {

                    if (fragment == null) {
                        window.history.replaceState({name: currentView.id}, null, "#" + currentView.id);
                    } else {
                        window.history.pushState({name: currentView.id}, null, "#" + currentView.id);
                    }
                }

                self.setViewVariables(view);
                return view;
            }

            /**
             * Set the currentView or currentOverlay properties and set the lastView or lastOverlay properties
             */
            self.setViewVariables = function (view, overlay, parentView) {
                if (view) {
                    if (self.currentView) {
                        self.lastView = self.currentView;
                    }
                    self.currentView = view;
                }

                if (overlay) {
                    if (self.currentOverlay) {
                        self.lastOverlay = self.currentOverlay;
                    }
                    self.currentOverlay = overlay;
                }
            }

            self.getViewPreferenceBoolean = function (view, property, altValue) {
                var computedStyle = window.getComputedStyle(view);
                var value = computedStyle.getPropertyValue(property);
                var type = typeof value;

                if (value == "true" || (type == "string" && value.indexOf("true") != -1)) {
                    return true;
                } else if (value == "" && arguments.length == 3) {
                    return altValue;
                }

                return false;
            }

            self.getViewPreferenceValue = function (view, property, defaultValue) {
                var value = window.getComputedStyle(view).getPropertyValue(property);

                if (value === undefined) {
                    return defaultValue;
                }

                value = value.replace(/^[\s\"]*/, "");
                value = value.replace(/[\s\"]*$/, "");
                value = value.replace(/^[\s"]*(.*?)[\s"]*$/, function (match, capture) {
                    return capture;
                });

                return value;
            }

            self.getStyleRuleValue = function (cssRule, property) {
                var value = cssRule ? cssRule.style.getPropertyValue(property) : null;

                if (value === undefined) {
                    return null;
                }

                value = value.replace(/^[\s\"]*/, "");
                value = value.replace(/[\s\"]*$/, "");
                value = value.replace(/^[\s"]*(.*?)[\s"]*$/, function (match, capture) {
                    return capture;
                });

                return value;
            }

            /**
             * Get the first defined value of property. Returns empty string if not defined
             * @param {String} id id of element
             * @param {String} property
             */
            self.getCSSPropertyValueForElement = function (id, property) {
                var styleSheets = document.styleSheets;
                var numOfStylesheets = styleSheets.length;
                var values = [];
                var selectorIDText = "#" + id;
                var selectorClassText = "." + id + "_Class";
                var value;

                for (var i = 0; i < numOfStylesheets; i++) {
                    var styleSheet = styleSheets[i];
                    var cssRules = self.getStylesheetRules(styleSheet);
                    var numOfCSSRules = cssRules.length;
                    var cssRule;

                    for (var j = 0; j < numOfCSSRules; j++) {
                        cssRule = cssRules[j];

                        if (cssRule.media) {
                            var mediaRules = cssRule.cssRules;
                            var numOfMediaRules = mediaRules ? mediaRules.length : 0;

                            for (var k = 0; k < numOfMediaRules; k++) {
                                var mediaRule = mediaRules[k];

                                if (mediaRule.selectorText == selectorIDText || mediaRule.selectorText == selectorClassText) {

                                    if (mediaRule.style && mediaRule.style.getPropertyValue(property) != "") {
                                        value = mediaRule.style.getPropertyValue(property);
                                        values.push(value);
                                    }
                                }
                            }
                        } else {

                            if (cssRule.selectorText == selectorIDText || cssRule.selectorText == selectorClassText) {
                                if (cssRule.style && cssRule.style.getPropertyValue(property) != "") {
                                    value = cssRule.style.getPropertyValue(property);
                                    values.push(value);
                                }
                            }
                        }
                    }
                }

                return values.pop();
            }

            self.getIsStyleDefined = function (id, property) {
                var value = self.getCSSPropertyValueForElement(id, property);
                return value !== undefined && value != "";
            }

            self.collectViews = function () {
                var viewIds = self.getViewIds();

                for (let index = 0; index < viewIds.length; index++) {
                    const id = viewIds[index];
                    const view = self.getElement(id);
                    self.views[id] = view;
                }

                self.viewIds = viewIds;
            }

            self.collectOverlays = function () {
                var viewIds = self.getViewIds();
                var ids = [];

                for (let index = 0; index < viewIds.length; index++) {
                    const id = viewIds[index];
                    const view = self.getViewById(id);
                    const isOverlay = view && self.isOverlay(view);

                    if (isOverlay) {
                        ids.push(id);
                        self.overlays[id] = view;
                    }
                }

                self.overlayIds = ids;
            }

            self.collectMediaQueries = function () {
                var viewIds = self.getViewIds();
                var styleSheet = self.getApplicationStylesheet();
                var cssRules = self.getStylesheetRules(styleSheet);
                var numOfCSSRules = cssRules ? cssRules.length : 0;
                var cssRule;
                var id = viewIds.length ? viewIds[0] : ""; // single view
                var selectorIDText = "#" + id;
                var selectorClassText = "." + id + "_Class";
                var viewsNotFound = viewIds.slice();
                var viewsFound = [];
                var selectorText = null;
                var property = self.prefix + "view-id";
                var stateName = self.prefix + "state";
                var stateValue = null;
                var view = null;

                for (var j = 0; j < numOfCSSRules; j++) {
                    cssRule = cssRules[j];

                    if (cssRule.media) {
                        var mediaRules = cssRule.cssRules;
                        var numOfMediaRules = mediaRules ? mediaRules.length : 0;
                        var mediaViewInfoFound = false;
                        var mediaId = null;

                        for (var k = 0; k < numOfMediaRules; k++) {
                            var mediaRule = mediaRules[k];

                            selectorText = mediaRule.selectorText;

                            if (selectorText == ".mediaViewInfo" && mediaViewInfoFound == false) {

                                mediaId = self.getStyleRuleValue(mediaRule, property);
                                stateValue = self.getStyleRuleValue(mediaRule, stateName);

                                selectorIDText = "#" + mediaId;
                                selectorClassText = "." + mediaId + "_Class";
                                view = self.getElement(mediaId);

                                // prevent duplicates from load and domcontentloaded events
                                if (self.addedViews.indexOf(mediaId) == -1) {
                                    self.addView(view, mediaId, cssRule, mediaRule, stateValue);
                                }

                                viewsFound.push(mediaId);

                                if (viewsNotFound.indexOf(mediaId) != -1) {
                                    viewsNotFound.splice(viewsNotFound.indexOf(mediaId));
                                }

                                mediaViewInfoFound = true;
                            }

                            if (selectorIDText == selectorText || selectorClassText == selectorText) {
                                var styleObject = self.viewsDictionary[mediaId];
                                if (styleObject) {
                                    styleObject.styleDeclaration = mediaRule;
                                }
                                break;
                            }
                        }
                    } else {
                        selectorText = cssRule.selectorText;

                        if (selectorText == null) continue;

                        selectorText = selectorText.replace(/[#|\s|*]?/g, "");

                        if (viewIds.indexOf(selectorText) != -1) {
                            view = self.getElement(selectorText);
                            self.addView(view, selectorText, cssRule, null, stateValue);

                            if (viewsNotFound.indexOf(selectorText) != -1) {
                                viewsNotFound.splice(viewsNotFound.indexOf(selectorText));
                            }

                            break;
                        }
                    }
                }

                if (viewsNotFound.length) {
                    console.log("Could not find the following views:" + viewsNotFound.join(",") + "");
                    console.log("Views found:" + viewsFound.join(",") + "");
                }
            }

            /**
             * Adds a view
             * @param {HTMLElement} view view element
             * @param {String} id id of view element
             * @param {CSSRule} cssRule of view element
             * @param {CSSMediaRule} mediaRule media rule of view element
             * @param {String} stateName name of state if applicable
             **/
            self.addView = function (view, viewId, cssRule, mediaRule, stateName) {
                var viewData = {};
                viewData.name = viewId;
                viewData.rule = cssRule;
                viewData.id = viewId;
                viewData.mediaRule = mediaRule;
                viewData.stateName = stateName;

                self.views.push(viewData);
                self.addedViews.push(viewId);
                self.viewsDictionary[viewId] = viewData;
                self.mediaQueryDictionary[viewId] = cssRule;
            }

            self.hasView = function (name) {

                if (self.addedViews.indexOf(name) != -1) {
                    return true;
                }
                return false;
            }

            /**
             * Go to view by id. Views are added in addView()
             * @param {String} id id of view in current
             * @param {Boolean} maintainPreviousState if true then do not hide other views
             * @param {String} parent id of parent view
             **/
            self.goToView = function (id, maintainPreviousState, parent) {
                var state = self.viewsDictionary[id];

                if (state) {
                    if (maintainPreviousState == false || maintainPreviousState == null) {
                        self.hideViews();
                    }
                    self.enableMediaQuery(state.rule);
                    self.updateViewLabel();
                    self.updateURL();
                } else {
                    var event = new Event(self.STATE_NOT_FOUND);
                    self.stateName = id;
                    window.dispatchEvent(event);
                }
            }

            /**
             * Go to the view in the event targets CSS variable
             **/
            self.goToTargetView = function (event) {
                var button = event.currentTarget;
                var buttonComputedStyles = getComputedStyle(button);
                var actionTargetValue = buttonComputedStyles.getPropertyValue(self.prefix + "action-target").trim();
                var animation = buttonComputedStyles.getPropertyValue(self.prefix + "animation").trim();
                var targetType = buttonComputedStyles.getPropertyValue(self.prefix + "action-type").trim();
                var targetView = self.application ? null : self.getElement(actionTargetValue);
                var targetState = targetView ? self.getStateNameByViewId(targetView.id) : null;
                var actionTargetStyles = targetView ? targetView.style : null;
                var state = self.viewsDictionary[actionTargetValue];

                // navigate to page
                if (self.application == false || targetType == "page") {
                    document.location.href = "./" + actionTargetValue;
                    return;
                }

                // if view is found
                if (targetView) {

                    if (self.currentOverlay) {
                        self.removeOverlay(false);
                    }

                    if (self.showByMediaQuery) {
                        var stateName = targetState;

                        if (stateName == null || stateName == "") {
                            var initialView = self.getInitialView();
                            stateName = initialView ? self.getStateNameByViewId(initialView.id) : null;
                        }
                        self.showMediaQueryViewsByState(stateName, event);
                        return;
                    }

                    // add animation set in event target style declaration
                    if (animation && self.supportAnimations) {
                        self.crossFade(self.currentView, targetView, false, animation);
                    } else {
                        self.setViewVariables(self.currentView);
                        self.hideViews();
                        self.enableMediaQuery(state.rule);
                        self.scaleViewIfNeeded(targetView);
                        self.centerView(targetView);
                        self.updateViewLabel();
                        self.updateURL();
                    }
                } else {
                    var stateEvent = new Event(self.STATE_NOT_FOUND);
                    self.stateName = name;
                    window.dispatchEvent(stateEvent);
                }

                event.stopImmediatePropagation();
            }

            /**
             * Cross fade between views
             **/
            self.crossFade = function (from, to, update, animation) {
                var targetIndex = to.parentNode
                var fromIndex = Array.prototype.slice.call(from.parentElement.children).indexOf(from);
                var toIndex = Array.prototype.slice.call(to.parentElement.children).indexOf(to);

                if (from.parentNode == to.parentNode) {
                    var reverse = self.getReverseAnimation(animation);
                    var duration = self.getAnimationDuration(animation, true);

                    // if target view is above (higher index)
                    // then fade in target view
                    // and after fade in then hide previous view instantly
                    if (fromIndex < toIndex) {
                        self.setElementAnimation(from, null);
                        self.setElementAnimation(to, null);
                        self.showViewByMediaQuery(to);
                        self.fadeIn(to, update, animation);

                        setTimeout(function () {
                            self.setElementAnimation(to, null);
                            self.setElementAnimation(from, null);
                            self.hideView(from);
                            self.updateURL();
                            self.setViewVariables(to);
                            self.updateViewLabel();
                        }, duration)
                    }
                        // if target view is on bottom
                        // then show target view instantly
                    // and fade out current view
                    else if (fromIndex > toIndex) {
                        self.setElementAnimation(to, null);
                        self.setElementAnimation(from, null);
                        self.showViewByMediaQuery(to);
                        self.fadeOut(from, update, reverse);

                        setTimeout(function () {
                            self.setElementAnimation(to, null);
                            self.setElementAnimation(from, null);
                            self.hideView(from);
                            self.updateURL();
                            self.setViewVariables(to);
                        }, duration)
                    }
                }
            }

            self.fadeIn = function (element, update, animation) {
                self.showViewByMediaQuery(element);

                if (update) {
                    self.updateURL(element);

                    element.addEventListener("animationend", function (event) {
                        element.style.animation = null;
                        self.setViewVariables(element);
                        self.updateViewLabel();
                        element.removeEventListener("animationend", arguments.callee);
                    });
                }

                self.setElementAnimation(element, null);

                element.style.animation = animation;
            }

            self.fadeOutCurrentView = function (animation, update) {
                if (self.currentView) {
                    self.fadeOut(self.currentView, update, animation);
                }
                if (self.currentOverlay) {
                    self.fadeOut(self.currentOverlay, update, animation);
                }
            }

            self.fadeOut = function (element, update, animation) {
                if (update) {
                    element.addEventListener("animationend", function (event) {
                        element.style.animation = null;
                        self.hideView(element);
                        element.removeEventListener("animationend", arguments.callee);
                    });
                }

                element.style.animationPlayState = "paused";
                element.style.animation = animation;
                element.style.animationPlayState = "running";
            }

            self.getReverseAnimation = function (animation) {
                if (animation && animation.indexOf("reverse") == -1) {
                    animation += " reverse";
                }

                return animation;
            }

            /**
             * Get duration in animation string
             * @param {String} animation animation value
             * @param {Boolean} inMilliseconds length in milliseconds if true
             */
            self.getAnimationDuration = function (animation, inMilliseconds) {
                var duration = 0;
                var expression = /.+(\d\.\d)s.+/;

                if (animation && animation.match(expression)) {
                    duration = parseFloat(animation.replace(expression, "$" + "1"));
                    if (duration && inMilliseconds) duration = duration * 1000;
                }

                return duration;
            }

            self.setElementAnimation = function (element, animation, priority) {
                element.style.setProperty("animation", animation, "important");
            }

            self.getElement = function (id) {
                id = id ? id.trim() : id;
                var element = id ? document.getElementById(id) : null;

                return element;
            }

            self.getElementById = function (id) {
                id = id ? id.trim() : id;
                var element = id ? document.getElementById(id) : null;

                return element;
            }

            self.getElementByClass = function (className) {
                className = className ? className.trim() : className;
                var elements = document.getElementsByClassName(className);

                return elements.length ? elements[0] : null;
            }

            self.resizeHandler = function (event) {

                if (self.showByMediaQuery) {
                    if (self.enableDeepLinking) {
                        var stateName = self.getHashFragment();

                        if (stateName == null || stateName == "") {
                            var initialView = self.getInitialView();
                            stateName = initialView ? self.getStateNameByViewId(initialView.id) : null;
                        }
                        self.showMediaQueryViewsByState(stateName, event);
                    }
                } else {
                    var visibleViews = self.getVisibleViews();

                    for (let index = 0; index < visibleViews.length; index++) {
                        var view = visibleViews[index];
                        self.scaleViewIfNeeded(view);
                    }
                }

                window.dispatchEvent(new Event(self.APPLICATION_RESIZE));
            }

            self.scaleViewIfNeeded = function (view) {

                if (self.scaleViewsOnResize) {
                    if (view == null) {
                        view = self.getVisibleView();
                    }

                    var isViewScaled = view.getAttributeNS(null, self.SIZE_STATE_NAME) == "false" ? false : true;

                    if (isViewScaled) {
                        self.scaleViewToFit(view, true);
                    } else {
                        self.scaleViewToActualSize(view);
                    }
                } else if (view) {
                    self.centerView(view);
                }
            }

            self.centerView = function (view) {

                if (self.scaleViewsToFit) {
                    self.scaleViewToFit(view, true);
                } else {
                    self.scaleViewToActualSize(view);  // for centering support for now
                }
            }

            self.preventDoubleClick = function (event) {
                event.stopImmediatePropagation();
            }

            self.getHashFragment = function () {
                var value = window.location.hash ? window.location.hash.replace("#", "") : "";
                return value;
            }

            self.showBlockElement = function (view) {
                view.style.display = "block";
            }

            self.hideElement = function (view) {
                view.style.display = "none";
            }

            self.showStateFunction = null;

            self.showMediaQueryViewsByState = function (state, event) {
                // browser will hide and show by media query (small, medium, large)
                // but if multiple views exists at same size user may want specific view
                // if showStateFunction is defined that is called with state fragment and user can show or hide each media matching view by returning true or false
                // if showStateFunction is not defined and state is defined and view has a defined state that matches then show that and hide other matching views
                // if no state is defined show view
                // an viewChanging event is dispatched before views are shown or hidden that can be prevented

                // get all matched queries
                // if state name is specified then show that view and hide other views
                // if no state name is defined then show
                var matchedViews = self.getMatchingViews();
                var matchMediaQuery = true;
                var foundViews = self.getViewsByStateName(state, matchMediaQuery);
                var showViews = [];
                var hideViews = [];

                // loop views that match media query
                for (let index = 0; index < matchedViews.length; index++) {
                    var view = matchedViews[index];

                    // let user determine visible view
                    if (self.showStateFunction != null) {
                        if (self.showStateFunction(view, state)) {
                            showViews.push(view);
                        } else {
                            hideViews.push(view);
                        }
                    }
                    // state was defined so check if view matches state
                    else if (foundViews.length) {

                        if (foundViews.indexOf(view) != -1) {
                            showViews.push(view);
                        } else {
                            hideViews.push(view);
                        }
                    }
                    // if no state names are defined show view (define unused state name to exclude)
                    else if (state == null || state == "") {
                        showViews.push(view);
                    }
                }

                if (showViews.length) {
                    var viewChangingEvent = new Event(self.VIEW_CHANGING);
                    viewChangingEvent.showViews = showViews;
                    viewChangingEvent.hideViews = hideViews;
                    window.dispatchEvent(viewChangingEvent);

                    if (viewChangingEvent.defaultPrevented == false) {
                        for (var index = 0; index < hideViews.length; index++) {
                            var view = hideViews[index];

                            if (self.isOverlay(view)) {
                                self.removeOverlay(view);
                            } else {
                                self.hideElement(view);
                            }
                        }

                        for (var index = 0; index < showViews.length; index++) {
                            var view = showViews[index];

                            if (index == showViews.length - 1) {
                                self.clearDisplay(view);
                                self.setViewOptions(view);
                                self.setViewVariables(view);
                                self.centerView(view);
                                self.updateURLState(view, state);
                            }
                        }
                    }

                    var viewChangeEvent = new Event(self.VIEW_CHANGE);
                    viewChangeEvent.showViews = showViews;
                    viewChangeEvent.hideViews = hideViews;
                    window.dispatchEvent(viewChangeEvent);
                }

            }

            self.clearDisplay = function (view) {
                view.style.setProperty("display", null);
            }

            self.hashChangeHandler = function (event) {
                var fragment = self.getHashFragment();
                var view = self.getViewById(fragment);

                if (self.showByMediaQuery) {
                    var stateName = fragment;

                    if (stateName == null || stateName == "") {
                        var initialView = self.getInitialView();
                        stateName = initialView ? self.getStateNameByViewId(initialView.id) : null;
                    }
                    self.showMediaQueryViewsByState(stateName);
                } else {
                    if (view) {
                        self.hideViews();
                        self.showView(view);
                        self.setViewVariables(view);
                        self.updateViewLabel();

                        window.dispatchEvent(new Event(self.VIEW_CHANGE));
                    } else {
                        window.dispatchEvent(new Event(self.VIEW_NOT_FOUND));
                    }
                }
            }

            self.popStateHandler = function (event) {
                var state = event.state;
                var fragment = state ? state.name : window.location.hash;
                var view = self.getViewById(fragment);

                if (view) {
                    self.hideViews();
                    self.showView(view);
                    self.updateViewLabel();
                } else {
                    window.dispatchEvent(new Event(self.VIEW_NOT_FOUND));
                }
            }

            self.doubleClickHandler = function (event) {
                var view = self.getVisibleView();
                var scaleValue = view ? self.getViewScaleValue(view) : 1;
                var scaleNeededToFit = view ? self.getViewFitToViewportScale(view) : 1;
                var scaleNeededToFitWidth = view ? self.getViewFitToViewportWidthScale(view) : 1;
                var scaleNeededToFitHeight = view ? self.getViewFitToViewportHeightScale(view) : 1;
                var scaleToFitType = self.scaleToFitType;

                // Three scenarios
                // - scale to fit on double click
                // - set scale to actual size on double click
                // - switch between scale to fit and actual page size

                if (scaleToFitType == "width") {
                    scaleNeededToFit = scaleNeededToFitWidth;
                } else if (scaleToFitType == "height") {
                    scaleNeededToFit = scaleNeededToFitHeight;
                }

                // if scale and actual size enabled then switch between
                if (self.scaleToFitOnDoubleClick && self.actualSizeOnDoubleClick) {
                    var isViewScaled = view.getAttributeNS(null, self.SIZE_STATE_NAME);
                    var isScaled = false;

                    // if scale is not 1 then view needs scaling
                    if (scaleNeededToFit != 1) {

                        // if current scale is at 1 it is at actual size
                        // scale it to fit
                        if (scaleValue == 1) {
                            self.scaleViewToFit(view);
                            isScaled = true;
                        } else {
                            // scale is not at 1 so switch to actual size
                            self.scaleViewToActualSize(view);
                            isScaled = false;
                        }
                    } else {
                        // view is smaller than viewport
                        // so scale to fit() is scale actual size
                        // actual size and scaled size are the same
                        // but call scale to fit to retain centering
                        self.scaleViewToFit(view);
                        isScaled = false;
                    }

                    view.setAttributeNS(null, self.SIZE_STATE_NAME, isScaled + "");
                    isViewScaled = view.getAttributeNS(null, self.SIZE_STATE_NAME);
                } else if (self.scaleToFitOnDoubleClick) {
                    self.scaleViewToFit(view);
                } else if (self.actualSizeOnDoubleClick) {
                    self.scaleViewToActualSize(view);
                }

            }

            self.scaleViewToFit = function (view) {
                return self.setViewScaleValue(view, true);
            }

            self.scaleViewToActualSize = function (view) {
                self.setViewScaleValue(view, false, 1);
            }

            self.onloadHandler = function (event) {
                self.initialize();
            }

            self.setElementHTML = function (id, value) {
                var element = self.getElement(id);
                element.innerHTML = value;
            }

            self.getStackArray = function (error) {
                var value = "";

                if (error == null) {
                    try {
                        error = new Error("Stack");
                    } catch (e) {

                    }
                }

                if ("stack" in error) {
                    value = error.stack;
                    var methods = value.split(/\n/g);

                    var newArray = methods ? methods.map(function (value, index, array) {
                        value = value.replace(/\@.*/, "");
                        return value;
                    }) : null;

                    if (newArray && newArray[0].includes("getStackTrace")) {
                        newArray.shift();
                    }
                    if (newArray && newArray[0].includes("getStackArray")) {
                        newArray.shift();
                    }
                    if (newArray && newArray[0] == "") {
                        newArray.shift();
                    }

                    return newArray;
                }

                return null;
            }

            self.log = function (value) {
                console.log.apply(this, [value]);
            }

            // initialize on load
            // sometimes the body size is 0 so we call this now and again later
            window.addEventListener("load", self.onloadHandler);
            window.document.addEventListener("DOMContentLoaded", self.onloadHandler);
        }

        window.application = new Application();
    </script>
</head>
<body>
<div id="Homepage">
    <div id="Group_434">
        <svg class="Path_311" viewBox="0 -931.695 1400 911.228">
            <path id="Path_311"
                  d="M 0 -20.466796875 L 1400 -20.466796875 L 1400 -931.6950073242188 L 0 -931.6950073242188 L 0 -20.466796875 Z">
            </path>
        </svg>
    </div>
    <div id="Menu">
        <div id="MENU">
            <span>MENU</span>
        </div>
        <img id="Group_57" src="<?php echo home_url('/home-temp/');?>Group_57.png" srcset="<?php echo home_url('/home-temp/');?>Group_57.png 1x, <?php echo home_url('/home-temp/');?>Group_57@2x.png 2x">

        </svg>
    </div>
    <div id="Group_404">
        <img id="Group_52" src="<?php echo home_url('/home-temp/');?>Group_52.png" srcset="<?php echo home_url('/home-temp/');?>Group_52.png 1x, <?php echo home_url('/home-temp/');?>Group_52@2x.png 2x">

        </svg>
        <div id="SIGN_IN_o">
            <span>SIGN IN</span>
        </div>
        <div id="SIGN_IN_o">
            <span>SIGN IN</span>
        </div>
    </div>
    <div id="Search">
        <img id="Group_48" src="<?php echo home_url('/home-temp/');?>Group_48.png" srcset="<?php echo home_url('/home-temp/');?>Group_48.png 1x, <?php echo home_url('/home-temp/');?>Group_48@2x.png 2x">

        </svg>
        <div id="SEARCH">
            <span>SEARCH</span>
        </div>
    </div>
    <div id="Group_147">
        <div id="Group">
            <div id="Capabilities">
                <span>Capabilities</span>
            </div>
            <div id="PRODUCTS">
                <span>PRODUCTS</span>
            </div>
            <div id="INDUSTRIES">
                <span>INDUSTRIES</span>
            </div>
            <div id="INNOVATION">
                <span>INNOVATION</span>
            </div>
        </div>
    </div>
    <div id="Group_150">
        <img id="Group_149" src="<?php echo home_url('/home-temp/');?>Group_149.png" srcset="<?php echo home_url('/home-temp/');?>Group_149.png 1x, <?php echo home_url('/home-temp/');?>Group_149@2x.png 2x">

        </svg>
    </div>
    <div id="Nav_Arrow">
        <img id="Group_9" src="<?php echo home_url('/home-temp/');?>Group_9.png" srcset="<?php echo home_url('/home-temp/');?>Group_9.png 1x, <?php echo home_url('/home-temp/');?>Group_9@2x.png 2x">

        </svg>
        <div id="Group_10">
            <svg class="Path_9" viewBox="0 -34.518 12.585 25.169">
                <path id="Path_9"
                      d="M 0 -9.348824501037598 L 0 -9.348824501037598 L 12.58458805084229 -21.93341445922852 L 0 -34.51799774169922 L 0 -9.348824501037598 Z">
                </path>
            </svg>
        </div>
    </div>
    <div id="Nav_Arrow_">
        <img id="Group_9_" src="<?php echo home_url('/home-temp/');?>Group_9_.png" srcset="<?php echo home_url('/home-temp/');?>Group_9_.png 1x, <?php echo home_url('/home-temp/');?>Group_9_@2x.png 2x">

        </svg>
        <div id="Group_10_">
            <svg class="Path_9_" viewBox="0 0 12.585 25.169">
                <path id="Path_9_"
                      d="M 0 25.16917610168457 L 0 25.16917610168457 L 12.58458805084229 12.58458805084229 L 0 0 L 0 25.16917610168457 Z">
                </path>
            </svg>
        </div>
    </div>
    <div id="Sustainable_Growth">
        <span>Sustainable Growth</span>
    </div>
    <div id="for_more_than_100_years">
        <span>for more than 100 years</span>
    </div>
    <div id="Group_205">
        <div id="White_hollow_button">
            <div id="n_FIND_OUT_MORE">
                <span> FIND OUT MORE</span>
            </div>
        </div>
        <svg class="Rectangle_24">
            <rect id="Rectangle_24" rx="0" ry="0" x="0" y="0" width="257" height="59">
            </rect>
        </svg>
    </div>
    <img id="Untitled-28" src="<?php echo home_url('/home-temp/');?>Untitled-28.png" srcset="<?php echo home_url('/home-temp/');?>Untitled-28.png 1x, <?php echo home_url('/home-temp/');?>Untitled-28@2x.png 2x">

    <div id="Group_419">
        <div id="INNOVATION_bg">
            <span>INNOVATION</span>
        </div>
        <svg class="Line_1" viewBox="0 0 35 3">
            <path id="Line_1" d="M 0 0 L 34.99972534179688 0">
            </path>
        </svg>
    </div>
    <div id="Learn_more_about_our_areas_of_">
        <span>Learn more about our areas of innovation</span>
    </div>
    <div id="Nimin_nimus_aut_pa_sam_vel_inc">
        <span>Nimin nimus aut pa sam vel incia dolorem il molupta tiosani minctaturem quo volorer atemolu ptatias exernam voloreh entur?<br/>Equidebitet lit, et odi odi bernat harchictem eatureh entiore pudame plisqui Doluptatquos ut quiandes inimint faceaquatqui illiqui unte prae enimet aditia velitem et ommolupient aut quaspie ntotatur? </span>
    </div>
    <div id="Nimin_nimus_aut_pa_sam_vel_inc_bk">
        <span>Nimin nimus aut pa sam vel incia dolorem il molupta tiosani minctaturem quo volorer atemolu ptatias exernam voloreh entur?<br/>Equidebitet lit, et odi odi bernat harchictem eatureh entiore pudame plisqui Doluptatquos ut quiandes inimint faceaquatqui illiqui unte prae enimet aditia velitem et ommolupient aut quaspie ntotatur? </span>
    </div>
    <div id="Nimin_nimus_aut_pa_sam_vel_inc_bl">
        <span>Nimin nimus aut pa sam vel incia dolorem il molupta tiosani minctaturem quo volorer atemolu ptatias exernam voloreh entur?<br/>Equidebitet lit, et odi odi bernat harchictem eatureh entiore pudame plisqui Doluptatquos ut quiandes inimint faceaquatqui illiqui unte prae enimet aditia velitem et ommolupient aut quaspie ntotatur? </span>
    </div>
    <div id="Group_157">
        <img id="Group_154" src="<?php echo home_url('/home-temp/');?>Group_154.png" srcset="<?php echo home_url('/home-temp/');?>Group_154.png 1x, <?php echo home_url('/home-temp/');?>Group_154@2x.png 2x">

        </svg>
        <img id="Group_156" src="<?php echo home_url('/home-temp/');?>Group_156.png" srcset="<?php echo home_url('/home-temp/');?>Group_156.png 1x, <?php echo home_url('/home-temp/');?>Group_156@2x.png 2x">

        </svg>
    </div>
    <div id="Group_261">
        <div id="CAPABILITIES">
            <span>CAPABILITIES</span>
        </div>
        <svg class="Line_1_br" viewBox="0 0 35 3">
            <path id="Line_1_br" d="M 0 0 L 34.99972534179688 0">
            </path>
        </svg>
    </div>
    <div id="We_materialize_ideas__for_a_sa">
        <span>We materialize ideas <br/>for a safer, smarter and more sustainable world</span>
    </div>
    <div id="System-critical_elastomer_comp">
        <span>System-critical elastomer components for attractive markets.</span>
    </div>
    <div id="Red_Button" class="Red_Button">
        <svg class="Rectangle_26">
            <rect id="Rectangle_26" rx="0" ry="0" x="0" y="0" width="202" height="42">
            </rect>
        </svg>
        <svg class="Polygon_1" viewBox="0 0 24 12">
            <path id="Polygon_1" d="M 11.99999904632568 0 L 24 12 L 0 12 Z">
            </path>
        </svg>
        <div id="ABOUT_US">
            <span>ABOUT US</span>
        </div>
    </div>
    <div id="Material_Innovation__Solutions">
        <span>Material Innovation <br/>Solutions</span>
    </div>
    <div id="Smart_Material_and__Sensor_Sol">
        <span>Smart Material and <br/>Sensor Solutions</span>
    </div>
    <div id="Innovation_at_the_heart_of_wha">
        <span>Innovation at the heart of what we do</span>
    </div>
    <div id="Hydrogen_and__Battery_Solution">
        <span>Hydrogen and <br/>Battery Solutions</span>
    </div>
    <div id="Group_283">
        <div id="INDUSTRIES_b">
            <span>INDUSTRIES</span>
        </div>
        <svg class="Line_1_b" viewBox="0 0 35 3">
            <path id="Line_1_b" d="M 0 0 L 34.99972534179688 0">
            </path>
        </svg>
    </div>
    <div id="Serving_four_main_industries">
        <span>Serving four main industries</span>
    </div>
    <div id="Red_Button_b" class="Red_Button">
        <svg class="Rectangle_26_b">
            <rect id="Rectangle_26_b" rx="0" ry="0" x="0" y="0" width="322.289" height="41.562">
            </rect>
        </svg>
        <svg class="Polygon_1_b" viewBox="0 0 24.791 12.396">
            <path id="Polygon_1_b"
                  d="M 12.39573287963867 0 L 24.79146957397461 12.39573574066162 L 0 12.39573574066162 Z">
            </path>
        </svg>
        <div id="VIEW_OUR_INDUSTRIES">
            <span>VIEW OUR INDUSTRIES</span>
        </div>
    </div>
    <div id="Strategic_development_partner">
        <span>Strategic development partner</span>
    </div>
    <div id="Group_282">
        <svg class="Rectangle_27">
            <rect id="Rectangle_27" rx="0" ry="0" x="0" y="0" width="61.614" height="31.354">
            </rect>
        </svg>
        <div id="HVAC">
            <span>HVAC</span>
        </div>
    </div>
    <div id="GREY_HEADER" class="GREY_HEADER">
        <svg class="Rectangle_27_cf">
            <rect id="Rectangle_27_cf" rx="0" ry="0" x="0" y="0" width="101.06" height="31.354">
            </rect>
        </svg>
        <div id="OIL__GAS">
            <span>OIL & GAS</span>
        </div>
    </div>
    <div id="GREY_HEADER_ch" class="GREY_HEADER">
        <svg class="Rectangle_27_ci">
            <rect id="Rectangle_27_ci" rx="0" ry="0" x="0" y="0" width="73.201" height="31.354">
            </rect>
        </svg>
        <div id="WATER">
            <span>WATER</span>
        </div>
    </div>
    <div id="GREY_HEADER_ck" class="GREY_HEADER">
        <svg class="Rectangle_27_cl">
            <rect id="Rectangle_27_cl" rx="0" ry="0" x="0" y="0" width="142.851" height="31.354">
            </rect>
        </svg>
        <div id="POWER_TOOLS">
            <span>POWER TOOLS</span>
        </div>
    </div>
    <div id="Group_286">
        <img id="Group_285" src="<?php echo home_url('/home-temp/');?>Group_285.png" srcset="<?php echo home_url('/home-temp/');?>Group_285.png 1x, <?php echo home_url('/home-temp/');?>Group_285@2x.png 2x">

        </svg>
    </div>
    <div id="Group_289">
        <img id="<?php echo home_url('/home-temp/');?>Group_288" src="<?php echo home_url('/home-temp/');?>Group_288.png" srcset="<?php echo home_url('/home-temp/');?>Group_288.png 1x, <?php echo home_url('/home-temp/');?>Group_288@2x.png 2x">

        </svg>
    </div>
    <div id="Group_291">
        <div id="Group_290">
            <img id="Rectangle_35" src="<?php echo home_url('/home-temp/');?>Rectangle_35.png" srcset="<?php echo home_url('/home-temp/');?>Rectangle_35.png 1x, <?php echo home_url('/home-temp/');?>Rectangle_35@2x.png 2x">

        </div>
    </div>
    <div id="Group_294">
        <img id="Group_293" src="<?php echo home_url('/home-temp/');?>Group_293.png" srcset="<?php echo home_url('/home-temp/');?>Group_293.png 1x, <?php echo home_url('/home-temp/');?>Group_293@2x.png 2x">

        </svg>
    </div>
    <div id="Group_403">
        <svg class="Rectangle_38">
            <rect id="Rectangle_38" rx="0" ry="0" x="0" y="0" width="1401" height="387">
            </rect>
        </svg>
        <div id="Group_368">
            <div id="Group_349">
                <svg class="Path_265" viewBox="-8.505 0 7.442 7.442">
                    <path id="Path_265"
                          d="M -2.303486824035645 0 L -1.063184022903442 1.240302681922913 L -7.26323938369751 7.441816329956055 L -8.505000114440918 6.201513290405273 L -2.303486824035645 0 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_350">
                <svg class="Path_266" viewBox="0 -1.699 18.602 18.602">
                    <path id="Path_266"
                          d="M 0 -0.4601556360721588 L 17.36277961730957 16.90262413024902 L 18.60162544250488 15.66378021240234 L 1.240302681922913 -1.698999881744385 L 0 -0.4601556360721588 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_351">
                <svg class="Path_267" viewBox="0 -11.905 9.921 9.92">
                    <path id="Path_267"
                          d="M 0 -3.224339962005615 L 8.680659294128418 -11.90499973297119 L 9.920963287353516 -10.6661548614502 L 1.238844394683838 -1.985495328903198 L 0 -3.224339962005615 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_352">
                <svg class="Path_268" viewBox="-18.707 0 14.881 14.884">
                    <path id="Path_268"
                          d="M -5.066587924957275 0 L -3.826284885406494 1.240302681922913 L -17.46669578552246 14.88363265991211 L -18.70699882507324 13.64332962036133 L -5.066587924957275 0 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_353">
                <svg class="Path_269" viewBox="0 -1.701 13.643 13.642">
                    <path id="Path_269"
                          d="M 0 -0.4606973230838776 L 1.240302681922913 -1.700999975204468 L 13.64332962036133 10.7020263671875 L 12.40156745910645 11.9408712387085 L 0 -0.4606973230838776 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_354">
                <svg class="Path_270" viewBox="0 -1.701 9.921 9.921">
                    <path id="Path_270"
                          d="M 0 -0.4606973230838776 L 1.238844394683838 -1.700999975204468 L 9.920963287353516 6.979660987854004 L 8.680659294128418 8.219962120056152 L 0 -0.4606973230838776 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_355">
                <svg class="Path_271" viewBox="0 -11.905 9.921 9.92">
                    <path id="Path_271"
                          d="M 0 -3.224339962005615 L 8.680659294128418 -11.90499973297119 L 9.920963287353516 -10.6661548614502 L 1.240302681922913 -1.985495328903198 L 0 -3.224339962005615 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_356">
                <svg class="Path_272" viewBox="-1.699 0 14.882 14.882">
                    <path id="Path_272"
                          d="M -0.4601556360721588 0 L -1.698999881744385 1.238844394683838 L 11.94287109375 14.88217449188232 L 13.1831750869751 13.64187145233154 L -0.4601556360721588 0 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_357">
                <svg class="Path_273" viewBox="-1.701 0 4.961 4.96">
                    <path id="Path_273"
                          d="M -0.4606973230838776 0 L 3.260210752487183 3.719449520111084 L 2.01990818977356 4.959752559661865 L -1.700999975204468 1.238844394683838 L -0.4606973230838776 0 Z">
                    </path>
                </svg>
            </div>
            <img id="Group_359" src="<?php echo home_url('/home-temp/');?>Group_359.png" srcset="<?php echo home_url('/home-temp/');?>Group_359.png 1x, <?php echo home_url('/home-temp/');?>Group_359@2x.png 2x">

            </svg>
            <div id="Group_360">
                <svg class="Path_276" viewBox="0 -6.118 16.119 18.375">
                    <path id="Path_276"
                          d="M 0 -1.656993746757507 L 5.513914585113525 -1.656993746757507 L 5.513914585113525 12.25685501098633 L 10.60637378692627 12.25685501098633 L 10.60637378692627 -1.656993746757507 L 16.11883163452148 -1.656993746757507 L 16.11883163452148 -6.118000507354736 L 0 -6.118000507354736 L 0 -1.656993746757507 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_361">
                <svg class="Path_277" viewBox="-51.73 -9.252 47.222 18.56">
                    <path id="Path_277"
                          d="M -14.01050662994385 -2.505803346633911 L -17.79047584533691 -9.199501037597656 L -23.19501495361328 -9.199501037597656 L -23.59313774108887 -9.199501037597656 L -28.47268104553223 -9.199501037597656 L -31.6751594543457 1.431665301322937 L -35.21887969970703 -9.25200080871582 L -39.60259246826172 -9.25200080871582 L -43.14631652832031 1.431665301322937 L -46.35025024414062 -9.199501037597656 L -51.72999954223633 -9.199501037597656 L -45.58900451660156 9.308060646057129 L -41.15279388427734 9.308060646057129 L -37.47636032104492 -1.272063136100769 L -33.77514266967773 9.308060646057129 L -29.33892631530762 9.308060646057129 L -23.33574485778809 -8.77658748626709 L -16.60923767089844 2.297908306121826 L -16.60923767089844 9.175353050231934 L -11.49052619934082 9.175353050231934 L -11.49052619934082 2.220617294311523 L -4.508083343505859 -9.199501037597656 L -10.20428848266602 -9.199501037597656 L -14.01050662994385 -2.505803346633911 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_362">
                <svg class="Path_278" viewBox="-6.986 0 13.993 18.375">
                    <path id="Path_278"
                          d="M -1.89208197593689 0 L -6.986000061035156 0 L -6.986000061035156 18.37485694885254 L 7.006597995758057 18.37485694885254 L 7.006597995758057 13.91238975524902 L -1.89208197593689 13.91238975524902 L -1.89208197593689 0 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_363">
                <svg class="Path_279" viewBox="-6.912 -15.264 14.911 18.375">
                    <path id="Path_279"
                          d="M -1.872040152549744 -4.134088516235352 L 6.949347972869873 -4.134088516235352 L 6.949347972869873 -8.151764869689941 L -1.872040152549744 -8.151764869689941 L -1.872040152549744 -10.93424320220947 L 7.868090152740479 -10.93424320220947 L 7.868090152740479 -15.26399993896484 L -6.912000179290771 -15.26399993896484 L -6.912000179290771 3.110853910446167 L 7.999340057373047 3.110853910446167 L 7.999340057373047 -1.220361232757568 L -1.872040152549744 -1.220361232757568 L -1.872040152549744 -4.134088516235352 Z">
                    </path>
                </svg>
            </div>
            <img id="Group_365" src="<?php echo home_url('/home-temp/');?>Group_365.png" srcset="<?php echo home_url('/home-temp/');?>Group_365.png 1x, <?php echo home_url('/home-temp/');?>Group_365@2x.png 2x">

            </svg>
            <div id="Group_366">
                <svg class="Path_282" viewBox="-9.4 0 8.798 4.6">
                    <path id="Path_282"
                          d="M -2.545887470245361 0 L -7.454599380493164 0 L -9.399999618530273 4.600275993347168 L -0.6019445657730103 4.600275993347168 L -2.545887470245361 0 Z">
                    </path>
                </svg>
            </div>
            <div id="Group_367">
                <svg class="Path_283" viewBox="-14.211 0 20.555 12.154">
                    <path id="Path_283"
                          d="M -3.848894834518433 0 L -1.924639225006104 4.908710956573486 L -6.045856952667236 4.908710956573486 L -4.096809864044189 0 L -9.072603225708008 0 L -14.21100044250488 12.15365314483643 L -8.854584693908691 12.15365314483643 L -7.542094707489014 8.872430801391602 L -0.4546506106853485 8.872430801391602 L 0.8840886354446411 12.15365314483643 L 6.344046115875244 12.15365314483643 L 1.207106947898865 0 L -3.848894834518433 0 Z">
                    </path>
                </svg>
            </div>
        </div>
        <div id="Terms_of_Service">
            <span>Terms of Service</span>
        </div>
        <div id="Sitemap">
            <span>Sitemap</span>
        </div>
        <div id="News">
            <span>News</span>
        </div>
        <div id="Datwyler_Group">
            <span>Datwyler Group</span>
        </div>
        <div id="Mobility">
            <span>Mobility</span>
        </div>
        <div id="Healthcare">
            <span>Healthcare</span>
        </div>
        <div id="Connectors">
            <span>Connectors</span>
        </div>
        <div id="Food__Beverage">
            <span>Food & Beverage</span>
        </div>
        <div id="Cookie_Consent">
            <span>Cookie Consent</span>
        </div>
        <div id="Contact">
            <span>Contact</span>
        </div>
        <div id="Keep_Me_Informed">
            <span>Keep Me Informed</span>
        </div>
        <div id="Data_Privacy">
            <span>Data Privacy</span>
        </div>
        <div id="Our_brands">
            <span>Our brands</span>
        </div>
        <div id="Follow_us">
            <span>Follow us</span>
        </div>
        <div id="Careers">
            <span>Careers</span>
        </div>
        <div id="Environmental_Policy">
            <span>Environmental Policy</span>
        </div>
        <svg class="Line_2" viewBox="0 0 566.558 1">
            <path id="Line_2" d="M 0 0 L 566.5579833984375 0">
            </path>
        </svg>
        <svg class="Line_3" viewBox="0 0 566.558 1">
            <path id="Line_3" d="M 0 0 L 566.5579833984375 0">
            </path>
        </svg>
        <div id="Group_371">
            <div id="Group_370">
                <img id="Group_369" src="<?php echo home_url('/home-temp/');?>Group_369.png" srcset="<?php echo home_url('/home-temp/');?>Group_369.png 1x, <?php echo home_url('/home-temp/');?>Group_369@2x.png 2x">

                </svg>
            </div>
        </div>
        <div id="Group_374">
            <div id="Group_373">
                <img id="Group_372" src="<?php echo home_url('/home-temp/');?>Group_372.png" srcset="<?php echo home_url('/home-temp/');?>Group_372.png 1x, <?php echo home_url('/home-temp/');?>Group_372@2x.png 2x">

                </svg>
            </div>
        </div>
        <div id="Group_377">
            <div id="Group_376">
                <img id="Group_375" src="<?php echo home_url('/home-temp/');?>Group_375.png" srcset="<?php echo home_url('/home-temp/');?>Group_375.png 1x, <?php echo home_url('/home-temp/');?>Group_375@2x.png 2x">

                </svg>
            </div>
        </div>
        <div id="Group_380">
            <div id="Group_379">
                <img id="Group_378" src="<?php echo home_url('/home-temp/');?>Group_378.png" srcset="<?php echo home_url('/home-temp/');?>Group_378.png 1x, <?php echo home_url('/home-temp/');?>Group_378@2x.png 2x">

                </svg>
            </div>
        </div>
        <div id="Group_383">
            <img id="Group_382" src="<?php echo home_url('/home-temp/');?>Group_382.png" srcset="<?php echo home_url('/home-temp/');?>Group_382.png 1x, <?php echo home_url('/home-temp/');?>Group_382@2x.png 2x">

            </svg>
        </div>
        <div id="Group_389">
            <div id="Group_400">
                <div id="Group_385">
                    <svg class="Path_287" viewBox="-14.703 -2.649 21.442 21.442">
                        <path id="Path_287"
                              d="M -3.982147693634033 -0.7174528241157532 C -1.119461536407471 -0.7174528241157532 -0.7804021835327148 -0.7065154314041138 0.3497972786426544 -0.6547449827194214 C 1.395413994789124 -0.6073495149612427 1.962701082229614 -0.4323509037494659 2.340406179428101 -0.2857895791530609 C 2.841339826583862 -0.09110362082719803 3.197900056838989 0.1407696008682251 3.573417901992798 0.5162874460220337 C 3.948206901550293 0.8910761475563049 4.180809020996094 1.248365044593811 4.375495433807373 1.748569369316101 C 4.522056102752686 2.126274347305298 4.697055339813232 2.694290637969971 4.744450569152832 3.739178419113159 C 4.796220779418945 4.870107173919678 4.80715799331665 5.209167003631592 4.80715799331665 8.07185173034668 C 4.80715799331665 10.93453693389893 4.796220779418945 11.27359771728516 4.744450569152832 12.4037971496582 C 4.697055339813232 13.4486837387085 4.522056102752686 14.01670169830322 4.375495433807373 14.39440536499023 C 4.180809020996094 14.89461040496826 3.948206901550293 15.25189876556396 3.573417901992798 15.62741756439209 C 3.197900056838989 16.00220680236816 2.841339826583862 16.23480987548828 2.340406179428101 16.42876625061035 C 1.962701082229614 16.57605743408203 1.395413994789124 16.75032424926758 0.3497972786426544 16.7984504699707 C -0.7804021835327148 16.8494930267334 -1.119461536407471 16.86042976379395 -3.982147693634033 16.86042976379395 C -6.844832897186279 16.86042976379395 -7.183892250061035 16.8494930267334 -8.314092636108398 16.7984504699707 C -9.359708786010742 16.75032424926758 -9.926996231079102 16.57605743408203 -10.30470085144043 16.42876625061035 C -10.80563449859619 16.23480987548828 -11.16219520568848 16.00220680236816 -11.53771305084229 15.62741756439209 C -11.91250133514404 15.25189876556396 -12.14510345458984 14.89461040496826 -12.33978939056396 14.39440536499023 C -12.48635005950928 14.01670169830322 -12.66134929656982 13.4486837387085 -12.70874500274658 12.4037971496582 C -12.7605152130127 11.27359771728516 -12.77145195007324 10.93453693389893 -12.77145195007324 8.07185173034668 C -12.77145195007324 5.209167003631592 -12.7605152130127 4.870107173919678 -12.70874500274658 3.739178419113159 C -12.66134929656982 2.694290637969971 -12.48635005950928 2.126274347305298 -12.33978939056396 1.748569369316101 C -12.14510345458984 1.248365044593811 -11.91250133514404 0.8910761475563049 -11.53771305084229 0.5162874460220337 C -11.16219520568848 0.1407696008682251 -10.80563449859619 -0.09110362082719803 -10.30470085144043 -0.2857895791530609 C -9.926996231079102 -0.4323509037494659 -9.359708786010742 -0.6073495149612427 -8.314092636108398 -0.6547449827194214 C -7.183892250061035 -0.7065154314041138 -6.844832897186279 -0.7174528241157532 -3.982147693634033 -0.7174528241157532 M -3.982147693634033 -2.64900016784668 C -6.893686771392822 -2.64900016784668 -7.258996963500977 -2.636604309082031 -8.402320861816406 -2.584834098815918 C -9.54345703125 -2.532334327697754 -10.32293033599854 -2.351502418518066 -11.00469493865967 -2.086087942123413 C -11.70979404449463 -1.812652230262756 -12.30770587921143 -1.445884585380554 -12.9034309387207 -0.8494308590888977 C -13.4998836517334 -0.2537065446376801 -13.86592292785645 0.3442053496837616 -14.14008712768555 1.049304127693176 C -14.4047737121582 1.731069564819336 -14.58633422851562 2.510542154312134 -14.63810443878174 3.651679277420044 C -14.6906042098999 4.795003414154053 -14.70300006866455 5.160313129425049 -14.70300006866455 8.07185173034668 C -14.70300006866455 10.98339176177979 -14.6906042098999 11.34797286987305 -14.63810443878174 12.49202442169189 C -14.58633422851562 13.63316249847412 -14.4047737121582 14.41190624237061 -14.14008712768555 15.09440040588379 C -13.86592292785645 15.7987699508667 -13.4998836517334 16.39668464660645 -12.9034309387207 16.99313735961914 C -12.30770587921143 17.5888614654541 -11.70979404449463 17.95562934875488 -11.00469493865967 18.22979354858398 C -10.32293033599854 18.49448013305664 -9.54345703125 18.67604064941406 -8.402320861816406 18.72780990600586 C -7.258996963500977 18.78030967712402 -6.893686771392822 18.7927074432373 -3.982147693634033 18.7927074432373 C -1.070607900619507 18.7927074432373 -0.7052983045578003 18.78030967712402 0.4380256533622742 18.72780990600586 C 1.57916247844696 18.67604064941406 2.358635902404785 18.49448013305664 3.040400743484497 18.22979354858398 C 3.745499134063721 17.95562934875488 4.343411922454834 17.5888614654541 4.939135551452637 16.99313735961914 C 5.535590171813965 16.39668464660645 5.901628494262695 15.7987699508667 6.175793170928955 15.09440040588379 C 6.440478801727295 14.41190624237061 6.622039318084717 13.63316249847412 6.673810005187988 12.49202442169189 C 6.726309299468994 11.34797286987305 6.738705635070801 10.98339176177979 6.738705635070801 8.07185173034668 C 6.738705635070801 5.160313129425049 6.726309299468994 4.795003414154053 6.673810005187988 3.651679277420044 C 6.622039318084717 2.510542154312134 6.440478801727295 1.731069564819336 6.175793170928955 1.049304127693176 C 5.901628494262695 0.3442053496837616 5.535590171813965 -0.2537065446376801 4.939135551452637 -0.8494308590888977 C 4.343411922454834 -1.445884585380554 3.745499134063721 -1.812652230262756 3.040400743484497 -2.086087942123413 C 2.358635902404785 -2.351502418518066 1.57916247844696 -2.532334327697754 0.4380256533622742 -2.584834098815918 C -0.7052983045578003 -2.636604309082031 -1.070607900619507 -2.64900016784668 -3.982147693634033 -2.64900016784668">
                        </path>
                    </svg>
                </div>
                <div id="Group_386">
                    <svg class="Path_288" viewBox="-7.55 0 11.01 11.01">
                        <path id="Path_288"
                              d="M -2.044835567474365 0 C -5.085436820983887 0 -7.550000190734863 2.464564085006714 -7.550000190734863 5.505164623260498 C -7.550000190734863 8.54576587677002 -5.085436820983887 11.010329246521 -2.044835567474365 11.010329246521 C 0.995765745639801 11.010329246521 3.460329294204712 8.54576587677002 3.460329294204712 5.505164623260498 C 3.460329294204712 2.464564085006714 0.995765745639801 0 -2.044835567474365 0 M -2.044835567474365 9.078782081604004 C -4.018673896789551 9.078782081604004 -5.618453502655029 7.479002952575684 -5.618453502655029 5.505164623260498 C -5.618453502655029 3.531326055526733 -4.018673896789551 1.931547164916992 -2.044835567474365 1.931547164916992 C -0.07099689543247223 1.931547164916992 1.528782367706299 3.531326055526733 1.528782367706299 5.505164623260498 C 1.528782367706299 7.479002952575684 -0.07099689543247223 9.078782081604004 -2.044835567474365 9.078782081604004">
                        </path>
                    </svg>
                </div>
                <div id="Group_387">
                    <svg class="Path_289" viewBox="-3.529 -1.764 2.573 2.572">
                        <path id="Path_289"
                              d="M -0.9557913541793823 -0.4777602553367615 C -0.9557913541793823 0.2324424684047699 -1.5318284034729 0.8084795475006104 -2.24203085899353 0.8084795475006104 C -2.952962875366211 0.8084795475006104 -3.528999805450439 0.2324424684047699 -3.528999805450439 -0.4777602553367615 C -3.528999805450439 -1.187962889671326 -2.952962875366211 -1.764000058174133 -2.24203085899353 -1.764000058174133 C -1.5318284034729 -1.764000058174133 -0.9557913541793823 -1.187962889671326 -0.9557913541793823 -0.4777602553367615">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
        <div id="Group_393">
            <div id="Group_401">
                <div id="Group_390">
                    <svg class="Path_291" viewBox="0 0 1 1">
                        <path id="Path_291" d="M 0 0">
                        </path>
                    </svg>
                </div>
                <div id="Group_391">
                    <svg class="Path_292" viewBox="-9.505 -24.562 22.037 17.91">
                        <path id="Path_292"
                              d="M -2.574325561523438 -6.652350902557373 C 5.741754055023193 -6.652350902557373 10.28952980041504 -13.54219245910645 10.28952980041504 -19.51620864868164 C 10.28952980041504 -19.71235275268555 10.2858829498291 -19.90703773498535 10.27713394165039 -20.10099411010742 C 11.15941715240479 -20.73901176452637 11.92722511291504 -21.53525352478027 12.53242969512939 -22.44233131408691 C 11.72233104705811 -22.08212471008301 10.85025501251221 -21.83931350708008 9.935887336730957 -21.72994041442871 C 10.86921310424805 -22.28993797302246 11.58597850799561 -23.17586708068848 11.92430973052979 -24.2309627532959 C 11.05004501342773 -23.71326065063477 10.083176612854 -23.33701133728027 9.052873611450195 -23.13357543945312 C 8.228192329406738 -24.01294326782227 7.052785873413086 -24.56200218200684 5.752691745758057 -24.56200218200684 C 3.256044864654541 -24.56200218200684 1.231164813041687 -22.53712272644043 1.231164813041687 -20.04120254516602 C 1.231164813041687 -19.68683052062988 1.270539522171021 -19.34121131896973 1.348559856414795 -19.01017189025879 C -2.40953540802002 -19.19902420043945 -5.741071701049805 -20.99859237670898 -7.970845699310303 -23.73440361022949 C -8.359488487243652 -23.06649208068848 -8.583342552185059 -22.28993797302246 -8.583342552185059 -21.46160888671875 C -8.583342552185059 -19.89318466186523 -7.784909725189209 -18.50850677490234 -6.571585178375244 -17.69841003417969 C -7.313142776489258 -17.72101402282715 -8.009491920471191 -17.92517852783203 -8.619070053100586 -18.26423835754395 C -8.620529174804688 -18.24528121948242 -8.620529174804688 -18.22632217407227 -8.620529174804688 -18.20663642883301 C -8.620529174804688 -16.0162353515625 -7.061581611633301 -14.18822860717773 -4.992952346801758 -13.7740650177002 C -5.37284517288208 -13.67052459716797 -5.772425651550293 -13.61437797546387 -6.184401035308838 -13.61437797546387 C -6.476065635681152 -13.61437797546387 -6.758979797363281 -13.64354515075684 -7.034603118896484 -13.696044921875 C -6.459295272827148 -11.90012168884277 -4.79024600982666 -10.59273529052734 -2.810573816299438 -10.55627822875977 C -4.358582496643066 -9.342954635620117 -6.308358192443848 -8.620355606079102 -8.426571846008301 -8.620355606079102 C -8.791152954101562 -8.620355606079102 -9.151358604431152 -8.641500473022461 -9.505001068115234 -8.683063507080078 C -7.504181861877441 -7.401198863983154 -5.127847194671631 -6.652350902557373 -2.574325561523438 -6.652350902557373">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
        <div id="Group_399">
            <div id="Group_402">
                <div id="Group_394">
                </div>
                <div id="Group_395">
                    <svg class="Path_295" viewBox="-5.497 -2.943 13.044 13.772">
                        <path id="Path_295"
                              d="M -1.488802671432495 -0.7971470355987549 C -1.209533929824829 -1.111415386199951 -0.9783899784088135 -1.430058598518372 -0.6911007165908813 -1.701306462287903 C 0.1911838948726654 -2.533279180526733 1.236071586608887 -2.95327615737915 2.455228328704834 -2.943067789077759 C 3.125327587127686 -2.937234401702881 3.788134813308716 -2.88910961151123 4.434171199798584 -2.701715469360352 C 5.912179946899414 -2.274427175521851 6.771132469177246 -1.257247567176819 7.180191040039062 0.186490997672081 C 7.487896919250488 1.269295334815979 7.544771671295166 2.380536079406738 7.546229839324951 3.495422840118408 C 7.550604820251465 5.847696304321289 7.539666652679443 8.199969291687012 7.544771671295166 10.55224227905273 C 7.544771671295166 10.77244853973389 7.483522891998291 10.83005332946777 7.266961574554443 10.82859420776367 C 6.05509614944458 10.81838607788086 4.843230247497559 10.81765747070312 3.631365299224854 10.82859420776367 C 3.418450355529785 10.83078098297119 3.37178373336792 10.76442813873291 3.372513055801392 10.56317901611328 C 3.379804611206055 8.324655532836914 3.381262540817261 6.086861133575439 3.373971223831177 3.848336219787598 C 3.372513055801392 3.287612438201904 3.33678412437439 2.727616786956787 3.179285526275635 2.182204008102417 C 2.889079570770264 1.177420854568481 2.170126914978027 0.6655497550964355 1.117947578430176 0.7209659814834595 C -0.3192285895347595 0.7967987656593323 -1.0666184425354 1.508459806442261 -1.249637842178345 2.969697713851929 C -1.293387532234192 3.318965911865234 -1.313804149627686 3.66823410987854 -1.313804149627686 4.019690036773682 C -1.311616659164429 6.196234703063965 -1.315991520881653 8.372779846191406 -1.307970762252808 10.54932594299316 C -1.307241559028625 10.76661586761475 -1.361199378967285 10.83078098297119 -1.583593487739563 10.82859420776367 C -2.803479909896851 10.81765747070312 -4.024095058441162 10.81911468505859 -5.244710922241211 10.82786464691162 C -5.440855026245117 10.82932281494141 -5.497000217437744 10.77609443664551 -5.497000217437744 10.5784912109375 C -5.491167068481445 6.269151210784912 -5.491167068481445 1.959810376167297 -5.497000217437744 -2.349530696868896 C -5.497000217437744 -2.563174962997437 -5.427729606628418 -2.612757682800293 -5.225751876831055 -2.611299514770508 C -4.067115306854248 -2.60254955291748 -2.907749652862549 -2.601091384887695 -1.74838399887085 -2.611299514770508 C -1.535468935966492 -2.613487005233765 -1.480052828788757 -2.544216394424438 -1.485156774520874 -2.342238903045654 C -1.49682354927063 -1.827451348304749 -1.488802671432495 -1.312663793563843 -1.488802671432495 -0.7971470355987549">
                        </path>
                    </svg>
                </div>
                <div id="Group_396">
                    <svg class="Path_296" viewBox="-5.739 -9.244 4.19 13.442">
                        <path id="Path_296"
                              d="M -1.554345726966858 -2.503651142120361 C -1.554345726966858 -0.3715847432613373 -1.557991504669189 1.760481715202332 -1.549241542816162 3.891819000244141 C -1.54851233959198 4.128796100616455 -1.607574462890625 4.200982570648193 -1.852572441101074 4.198065757751465 C -3.063708543777466 4.18494176864624 -4.274845123291016 4.187858581542969 -5.486710071563721 4.196608543395996 C -5.680666923522949 4.19733715057373 -5.738999366760254 4.149212837219238 -5.738999366760254 3.948693990707397 C -5.733166694641113 -0.3679390847682953 -5.733166694641113 -4.684571743011475 -5.737541198730469 -9.001203536987305 C -5.737541198730469 -9.179848670959473 -5.694520950317383 -9.243285179138184 -5.504939079284668 -9.242555618286133 C -4.276303291320801 -9.234535217285156 -3.046937704086304 -9.23161792755127 -1.818301796913147 -9.244015693664551 C -1.582053780555725 -9.246201515197754 -1.549970626831055 -9.154327392578125 -1.550699830055237 -8.952349662780762 C -1.556533098220825 -6.802783966064453 -1.554345726966858 -4.653217792510986 -1.554345726966858 -2.503651142120361">
                        </path>
                    </svg>
                </div>
                <div id="Group_397">
                    <svg class="Path_297" viewBox="-6.636 -3.301 4.839 4.837">
                        <path id="Path_297"
                              d="M -1.797293424606323 -0.8940404057502747 C -1.795835018157959 0.4446989893913269 -2.882284641265869 1.534794569015503 -4.2181077003479 1.535523653030396 C -5.536430358886719 1.536252617835999 -6.631629943847656 0.4446989893913269 -6.63600492477417 -0.872894823551178 C -6.640379905700684 -2.205801010131836 -5.54590892791748 -3.302459239959717 -4.213003635406494 -3.301000833511353 C -2.889576196670532 -3.299542427062988 -1.798751711845398 -2.213092803955078 -1.797293424606323 -0.8940404057502747">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div id="Group_413">
        <div id="MY_QUOTES_3">
            <span>MY QUOTES (3)</span>
        </div>
        <div id="Group_412">
            <img id="Group_411" src="<?php echo home_url('/home-temp/');?>Group_411.png" srcset="<?php echo home_url('/home-temp/');?>Group_411.png 1x, <?php echo home_url('/home-temp/');?>Group_411@2x.png 2x">

            </svg>
        </div>
    </div>
    <div id="Group_432">
        <svg class="Line_4" viewBox="0 0 1401 1">
            <path id="Line_4" d="M 0 0 L 1401 0">
            </path>
        </svg>
        <div id="JUMP_TO">
            <span>JUMP TO:</span>
        </div>
        <div id="GREY_HEADER_fl" class="GREY_HEADER">
            <svg class="Rectangle_27_fm">
                <rect id="Rectangle_27_fm" rx="0" ry="0" x="0" y="0" width="130.883" height="43">
                </rect>
            </svg>
            <div id="CAPABILITIES_fn">
                <span>CAPABILITIES</span>
            </div>
        </div>
        <div id="GREY_HEADER_fo" class="GREY_HEADER">
            <svg class="Rectangle_27_fp">
                <rect id="Rectangle_27_fp" rx="0" ry="0" x="0" y="0" width="139.383" height="33.385">
                </rect>
            </svg>
            <div id="PRODUCTS_fq">
                <span>PRODUCTS</span>
            </div>
        </div>
        <div id="GREY_HEADER_fr" class="GREY_HEADER">
            <svg class="Rectangle_27_fs">
                <rect id="Rectangle_27_fs" rx="0" ry="0" x="0" y="0" width="139.383" height="33.385">
                </rect>
            </svg>
            <div id="INDUSTRIES_ft">
                <span>INDUSTRIES</span>
            </div>
        </div>
        <div id="GREY_HEADER_fu" class="GREY_HEADER">
            <svg class="Rectangle_27_fv">
                <rect id="Rectangle_27_fv" rx="0" ry="0" x="0" y="0" width="139.383" height="33.385">
                </rect>
            </svg>
            <div id="INNOVATION_fw">
                <span>INNOVATION</span>
            </div>
        </div>
    </div>
    <div id="Group_162">
        <svg class="Path_136" viewBox="0 -853.563 1399.989 656.87">
            <path id="Path_136"
                  d="M 0 -196.69287109375 L 1399.988891601562 -196.69287109375 L 1399.988891601562 -853.5629272460938 L 0 -853.5629272460938 L 0 -196.69287109375 Z">
            </path>
        </svg>
    </div>
    <div id="Group_262">
        <div id="PRODUCTS_f">
            <span>PRODUCTS</span>
        </div>
        <svg class="Line_1_f" viewBox="0 0 35 3">
            <path id="Line_1_f" d="M 0 0 L 34.99972534179688 0">
            </path>
        </svg>
    </div>
    <div id="Ga_Comnimpori_dolutestem_re_se">
        <span>Ga. Comnimpori dolutestem re sequo modis et harciis t</span>
    </div>
    <div id="Red_Button_f" class="Red_Button">
        <svg class="Rectangle_26_f">
            <rect id="Rectangle_26_f" rx="0" ry="0" x="0" y="0" width="300" height="42">
            </rect>
        </svg>
        <svg class="Polygon_1_f" viewBox="0 0 24 11">
            <path id="Polygon_1_f" d="M 11.99999904632568 0 L 24 11 L 0 11 Z">
            </path>
        </svg>
        <div id="VIEW_OUR_PRODUCTS">
            <span>VIEW OUR PRODUCTS</span>
        </div>
    </div>
    <div id="The_right_product_for_the_job">
        <span>The right product for the job</span>
    </div>
    <div id="Group_263">
        <svg class="Rectangle_27_f">
            <rect id="Rectangle_27_f" rx="0" ry="0" x="0" y="0" width="90" height="33">
            </rect>
        </svg>
        <div id="O-RINGS">
            <span>O-RINGS</span>
        </div>
    </div>
    <div id="GREY_HEADER_gb" class="GREY_HEADER">
        <svg class="Rectangle_27_gc">
            <rect id="Rectangle_27_gc" rx="0" ry="0" x="0" y="0" width="172.497" height="31.354">
            </rect>
        </svg>
        <div id="MACHINED_METAL">
            <span>MACHINED METAL</span>
        </div>
    </div>
    <div id="GREY_HEADER_ge" class="GREY_HEADER">
        <svg class="Rectangle_27_gf">
            <rect id="Rectangle_27_gf" rx="0" ry="0" x="0" y="0" width="238.201" height="31.354">
            </rect>
        </svg>
        <div id="PRODUCTION_EQUIPMENT">
            <span>PRODUCTION EQUIPMENT</span>
        </div>
    </div>
    <div id="GREY_HEADER_gh" class="GREY_HEADER">
        <svg class="Rectangle_27_gi">
            <rect id="Rectangle_27_gi" rx="0" ry="0" x="0" y="0" width="177.225" height="31.354">
            </rect>
        </svg>
        <div id="MOULDED_RUBBER">
            <span>MOULDED RUBBER</span>
        </div>
    </div>
    <div id="GREY_HEADER_gk" class="GREY_HEADER">
        <svg class="Rectangle_27_gl">
            <rect id="Rectangle_27_gl" rx="0" ry="0" x="0" y="0" width="263.201" height="31.354">
            </rect>
        </svg>
        <div id="MACHINED_THERMOPLASTICS">
            <span>MACHINED THERMOPLASTICS</span>
        </div>
    </div>
    <div id="Group_266">
        <img id="Group_265" src="<?php echo home_url('/home-temp/');?>Group_265.png" srcset="<?php echo home_url('/home-temp/');?>Group_265.png 1x, <?php echo home_url('/home-temp/');?>Group_265@2x.png 2x">

        </svg>
    </div>
    <div id="Group_270">
        <svg class="Path_214" viewBox="0 -382.826 282.239 279.142">
            <path id="Path_214"
                  d="M 0 -103.6842498779297 L 282.2392272949219 -103.6842498779297 L 282.2392272949219 -382.8260192871094 L 0 -382.8260192871094 L 0 -103.6842498779297 Z">
            </path>
        </svg>
        <img id="Group_269" src="<?php echo home_url('/home-temp/');?>Group_269.png" srcset="<?php echo home_url('/home-temp/');?>Group_269.png 1x, <?php echo home_url('/home-temp/');?>Group_269@2x.png 2x">

        </svg>
    </div>
    <div id="Group_418">
        <svg class="Path_214_gt" viewBox="0 -382.826 282.239 279.142">
            <path id="Path_214_gt"
                  d="M 0 -103.6842498779297 L 282.2392272949219 -103.6842498779297 L 282.2392272949219 -382.8260192871094 L 0 -382.8260192871094 L 0 -103.6842498779297 Z">
            </path>
        </svg>
        <img id="Group_269_gu" src="<?php echo home_url('/home-temp/');?>Group_269_gu.png" srcset="<?php echo home_url('/home-temp/');?>Group_269_gu.png 1x, <?php echo home_url('/home-temp/');?>Group_269_gu@2x.png 2x">

        </svg>
    </div>
    <div id="Group_273">
        <img id="Group_272" src="<?php echo home_url('/home-temp/');?>Group_272.png" srcset="<?php echo home_url('/home-temp/');?>Group_272.png 1x, <?php echo home_url('/home-temp/');?>Group_272@2x.png 2x">

        </svg>
    </div>
    <div id="Group_276">
        <svg class="Path_217" viewBox="0 -387.074 282.239 282.239">
            <path id="Path_217"
                  d="M 0 -104.8347854614258 L 282.2392272949219 -104.8347854614258 L 282.2392272949219 -387.0740356445312 L 0 -387.0740356445312 L 0 -104.8347854614258 Z">
            </path>
        </svg>
        <img id="Group_275" src="<?php echo home_url('/home-temp/');?>Group_275.png" srcset="<?php echo home_url('/home-temp/');?>Group_275.png 1x, <?php echo home_url('/home-temp/');?>Group_275@2x.png 2x">

        </svg>
    </div>
    <div id="Group_417">
        <img id="Group_416" src="<?php echo home_url('/home-temp/');?>Group_416.png" srcset="<?php echo home_url('/home-temp/');?>Group_416.png 1x, <?php echo home_url('/home-temp/');?>Group_416@2x.png 2x">

        </svg>
    </div>
    <div id="Group_429">
        <img id="Group_416_g" src="<?php echo home_url('/home-temp/');?>Group_416_g.png" srcset="<?php echo home_url('/home-temp/');?>Group_416_g.png 1x, <?php echo home_url('/home-temp/');?>Group_416_g@2x.png 2x">

        </svg>
    </div>
    <div id="Red_Button_g" class="Red_Button">
        <svg class="Rectangle_26_g">
            <rect id="Rectangle_26_g" rx="0" ry="0" x="0" y="0" width="277" height="57">
            </rect>
        </svg>
        <svg class="Polygon_1_g" viewBox="0 0 34 17">
            <path id="Polygon_1_g" d="M 16.99999809265137 0 L 34 17 L 0 17 Z">
            </path>
        </svg>
        <div id="FIND_OUT_MORE">
            <span>FIND OUT MORE</span>
        </div>
    </div>
    <div id="Red_Button_ha" class="Red_Button">
        <svg class="Rectangle_26_ha">
            <rect id="Rectangle_26_ha" rx="0" ry="0" x="0" y="0" width="277" height="57">
            </rect>
        </svg>
        <svg class="Polygon_1_ha" viewBox="0 0 34 17">
            <path id="Polygon_1_ha" d="M 16.99999809265137 0 L 34 17 L 0 17 Z">
            </path>
        </svg>
        <div id="FIND_OUT_MORE_hb">
            <span>FIND OUT MORE</span>
        </div>
    </div>
    <div id="Red_Button_hc" class="Red_Button">
        <svg class="Rectangle_26_hd">
            <rect id="Rectangle_26_hd" rx="0" ry="0" x="0" y="0" width="277" height="57">
            </rect>
        </svg>
        <svg class="Polygon_1_he" viewBox="0 0 34 17">
            <path id="Polygon_1_he" d="M 16.99999809265137 0 L 34 17 L 0 17 Z">
            </path>
        </svg>
        <div id="FIND_OUT_MORE_hf">
            <span>FIND OUT MORE</span>
        </div>
    </div>
    <div id="Group_422">
        <img id="Group_421" src="<?php echo home_url('/home-temp/');?>Group_421.png" srcset="<?php echo home_url('/home-temp/');?>Group_421.png 1x, <?php echo home_url('/home-temp/');?>Group_421@2x.png 2x">

        </svg>
    </div>
    <div id="Group_425">
        <img id="Group_424" src="<?php echo home_url('/home-temp/');?>Group_424.png" srcset="<?php echo home_url('/home-temp/');?>Group_424.png 1x, <?php echo home_url('/home-temp/');?>Group_424@2x.png 2x">

        </svg>
    </div>
    <div id="Group_428">
        <img id="Group_427" src="<?php echo home_url('/home-temp/');?>Group_427.png" srcset="<?php echo home_url('/home-temp/');?>Group_427.png 1x, <?php echo home_url('/home-temp/');?>Group_427@2x.png 2x">

        </svg>
    </div>
</div>
</body>
</html>