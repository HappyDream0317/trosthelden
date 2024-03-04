
<style type="text/css">
    * {
        box-sizing: border-box;
    }


    /* Marcellus-400 */
    @font-face {
        font-family: 'Marcellus';
        font-style: normal;
        font-weight: 400;
        src:  url("fonts/Marcellus-Regular.ttf") format("truetype");
    }

    /* IBM Plex Sans-300 */
    @font-face {
        font-family: 'IBM Plex Sans';
        font-style: normal;
        font-weight: 300;
        src: url("fonts/IBMPlexSans-Regular.ttf") format("truetype");
    }

    /* Source Sans Pro-400 */
    @font-face {
        font-family: 'Source Sans Pro';
        font-style: normal;
        font-weight: 400;
        src:  url("fonts/SourceSansPro-Regular.ttf") format("truetype");
    }

    /* lato-300 - latin */
    @font-face {
        font-family: "Lato";
        font-style: normal;
        font-weight: 300;
        src: url("fonts/lato-v16-latin-300.ttf") format("truetype")
    }

    /* lato-regular - latin */
    @font-face {
        font-family: "Lato";
        font-style: normal;
        font-weight: 400;
        src: url("fonts/lato-v16-latin-regular.ttf") format("truetype");
    }
    /* lato-700 - latin */
    @font-face {
        font-family: "Lato";
        font-style: normal;
        font-weight: 700;
        src: url("fonts/lato-v16-latin-700.ttf") format("truetype");
    }

    @page {
        margin: 0 80px;
    }

    body {
        margin-top: 130px;
        margin-bottom: 130px;
        font-family: "Marcellus", sans-serif;
        line-height: 16px;
    }


    header {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        padding-top: 30px ;
        height: 100px;
    }

    /** Define the footer rules **/
    footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 130px;
    }

    .page_break {
        page-break-before: always;
    }

    .quote {
        position: relative;
    }

    .quote:after {
        content:"";
        position: absolute;
        z-index: -1;
        top: 0;
        height: 25px;
        left: 50%;
        border-left: 1px solid #cedae4;
        transform: translate(-50%);
    }

    .quote:before {
        content:"";
        position: absolute;
        z-index: -1;
        bottom: 0;
        height: 45px;
        left: 50%;
        border-left: 1px solid #cedae4;
        transform: translate(-50%);
    }
    
    .code{
        position: relative;
    }

    .code:after {
        content:"";
        position: absolute;
        z-index: -1;
        top: 0;
        height: 25px;
        left: 50%;
        border-left: 1px solid #cedae4;
        transform: translate(-50%);
    }
</style>

