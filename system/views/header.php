<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?=INPHINIT_BASE_URL?>/favicon.ico">
<style type="text/css">
*, ::before, ::after {
    box-sizing: border-box;
}

body > .skip {
    padding: 16px;
    position: absolute;
    z-index: -1;
    width: 1px;
    height: 1px;
    margin: 0;
    clip: rect(1px, 1px, 1px, 1px);
    background: rgb(9, 105, 218);
    color: #fff;
}

body > .skip:focus {
    z-index: 999;
    width: auto;
    height: auto;
    clip: auto;
}

html, body {
    background: #262833;
    min-height: 100vh;
    padding: 0;
    margin: 0;
}

h1, h2, h3 {
    font-weight: bold;
}

html {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
    font-size: 16px;
    color: #F7F6F6;
    background-image: linear-gradient(135deg, #262833 10%, #101015 100%);
    background-size: cover;
}

body {
    min-width: 340px;
}

code {
    border-radius: .4rem;
    background: rgba(0, 0, 0, .5);
    display: inline-block;
    padding: .2rem .3rem;
    color: #fff;
}

main h1, main h2 {
    padding: .4rem 0;
    margin: 0;
}

main h1 {
    position: relative;
    font-weight: 100;
    padding: .4rem 0;
    margin: 0;
}

main h2 {
    font-weight: normal;
    font-size: 1.5rem;
}

.badge {
    background: #d71503;
    border-radius: 1rem;
    padding: .4rem 1rem;

    color: #fff;
    font-size: 0.92rem;
    font-weight: bolder;
    text-transform: uppercase;

    position: fixed;
    bottom: .5rem;
    right: .5rem;

    pointer-events: none;
}

section .badge {
    background: #d7a203;
    color: #000;
    font-size: 0.72rem;
    position: absolute;
    bottom: auto;
    top: 0.5rem;
    right: 0.5rem;
}

#intro, #error, #others, #samples {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

#intro {
    min-height: 320px;
}

#error {
    height: calc(100vh - 66px);
}

#others {
    flex-direction: column;
    height: 100vh;
}

#others section {
    flex: 1;
}

#others section {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 2rem;
}

#others h1 {
    margin-bottom: 2rem;
}

#samples {
    padding: 5rem 2rem 2rem 2rem;
}

#intro > header, #error > header {
    text-align: center;
    padding-bottom: 1rem;
}

#intro h1, #others h1, #samples h1 {
    font-size: 7.5rem;
    font-weight: bold;
    text-transform: uppercase;
    background: linear-gradient(135deg, #FD6E6A 10%, #FFC600 100%);
    background-clip: text;
    text-fill-color: transparent;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

#samples h1 {
    font-size: 4.2rem;
}

#error h1, #others h1 {
    font-size: 3.5rem;
}

#links {
    border-top: thin solid rgba(255,255,255,.1);
    padding: 1rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: .2rem;
    max-height: 66px;
    width: 100%;
}

#links > a {
    text-decoration: none;
    display: block;
    padding: .4rem .8rem;
    color: inherit;
    font-size: 1rem;
    border-radius: 1rem;
    background: transparent;
}

#links > a:hover, #links > a:active, #links > a:focus {
    background: rgba(255,255,255,.1);
}

@media (max-width: 510px) {
    body {
        font-size: 14px;
    }

    main > header {
        justify-content: center;
    }
}

@media (max-width: 410px) {
    main > header {
        justify-content: center;
    }
}

body * {
    transition: .3s all ease;
}

#items {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: .92rem;
    padding: .92rem;
    max-width: 2000px;
    margin: 0 auto;
}

#items > a {
    position: relative;
    flex: 1 0 28%;
    display: block;
    padding: 1.2rem;
    overflow: hidden;
    color: inherit;
    text-decoration: none;
    border-radius: .4rem;
    border: thin solid rgba(255,255,255,.2);
    background: rgba(0,0,0,.1);
    background-position: right top;
    background-repeat: no-repeat;
    background-image: url('data:image/svg+xml,<svg viewBox="0 0 200 200" width="200" height="200" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="grid" x1="2" y1="0" x2="1" y2="1"><stop offset="0" stop-color="%23595959" stop-opacity="1" /><stop offset="1" stop-color="%23595959" stop-opacity="0" /></linearGradient></defs><path d="M0,20 L200,20 M0,40 L200,40 M0,60 L200,60 M0,80 L200,80 M0,100 L200,100 M0,120 L200,120 M0,140 L200,140 M0,160 L200,160 M0,180 L200,180 M20,0 L20,200 M40,0 L40,200 M60,0 L60,200 M80,0 L80,200 M100,0 L100,200 M120,0 L120,200 M140,0 L140,200 M160,0 L160,200 M180,0 L180,200 M0,0 L200,0 L200,200 L0,200 Z" fill="none" stroke="url(%23grid)" stroke-width="1" shape-rendering="crispEdges" /></svg>');
}

#items > a:hover, #items > a:active, #items > a:focus {
    background-color: rgba(0,0,0,.24);
    border-color: rgba(255,255,255,.4);
}

#items h3 {
    margin: 0 0 1rem 0;
    text-transform: uppercase;
    font-size: 0.72rem;
}

#items > dl {
    flex: 1 0 28%;
    display: block;
    overflow: hidden;
    border-radius: .4rem;
    background: rgba(0,0,0,.1);
    border: thin solid rgba(255,255,255,.2);
}

#items > dl:hover {
    background-color: rgba(0,0,0,.24);
    border-color: rgba(255,255,255,.4);
}

#items > dl > dt, #items > dl > dd {
    list-style-type: none;
    margin: 0;
}

#items > dl > dt {
    background: rgba(0,0,0,.3);
    padding: 1rem;
    font-weight: bold;
    font-size: 80%;
    text-transform: uppercase;
}

#items > dl > dd, #items > dl > dd + dt {
    border-top: thin solid rgba(255,255,255,.2);
}

#items > dl > dd > a {
    color: inherit;
    display: block;
    padding: 1rem;
    text-decoration: none;
}

#items > dl > dd > a:hover,
#items > dl > dd > a:active,
#items > dl > dd > a:focus {
    background: rgba(255,255,255,.1);
}

@media (max-width: 1200px) {
    #items > a, #items > dl {
        flex: 1 0 48%;
    }
}

@media (max-width: 890px) {
    #intro {
        min-height: 180px;
    }

    #intro h1 {
        font-size: 3.2rem;
    }
}

table {
    border-collapse: collapse;
    border: thin solid #000;
    margin: 1%;
    width: 98%;
    background: rgba(255, 255, 255, .1);
}

td, th {
    padding: 1rem;
    border: thin solid #000;
}

thead {
    background: #6807f9;
}

th:first-child {
    width: 10%;
}

tbody > tr > :nth-child(odd) {
    background: rgba(255, 255, 255, .2);
}
</style>
