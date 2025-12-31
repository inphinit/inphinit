<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" href="<?=INPHINIT_URL?>/favicon.ico">
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
    font-weight: 600;
}

html {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
    font-size: 16px;
    color: #F7F6F6;
    background: linear-gradient( 135deg, #262833 10%, #101015 100%);
}

body {
    min-width: 340px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 800 800"><g fill="none" stroke="%23fff" stroke-width="1.5" stroke-opacity="0.1"><path d="M769 229L1037 260.9M927 880L731 737 520 660 309 538 40 599 295 764 126.5 879.5 40 599-197 493 102 382-31 229 126.5 79.5-69-63"/><path d="M-31 229L237 261 390 382 603 493 308.5 537.5 101.5 381.5M370 905L295 764"/><path d="M520 660L578 842 731 737 840 599 603 493 520 660 295 764 309 538 390 382 539 269 769 229 577.5 41.5 370 105 295 -36 126.5 79.5 237 261 102 382 40 599 -69 737 127 880"/><path d="M520-140L578.5 42.5 731-63M603 493L539 269 237 261 370 105M902 382L539 269M390 382L102 382"/><path d="M-222 42L126.5 79.5 370 105 539 269 577.5 41.5 927 80 769 229 902 382 603 493 731 737M295-36L577.5 41.5M578 842L295 764M40-201L127 80M102 382L-261 269"/></g><g fill="%23fcfcfc" fill-opacity="0.2"><circle cx="769" cy="229" r="4"/><circle cx="539" cy="269" r="4"/><circle cx="603" cy="493" r="4"/><circle cx="731" cy="737" r="4"/><circle cx="520" cy="660" r="4"/><circle cx="309" cy="538" r="4"/><circle cx="295" cy="764" r="4"/><circle cx="40" cy="599" r="4"/><circle cx="102" cy="382" r="4"/><circle cx="127" cy="80" r="4"/><circle cx="370" cy="105" r="4"/><circle cx="578" cy="42" r="4"/><circle cx="237" cy="261" r="4"/><circle cx="390" cy="382" r="4"/></g></svg>');
    background-size: 400px 400px;
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
    background: rgba(0,0,0,.1);
    border: thin solid rgba(255,255,255,.2);
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
