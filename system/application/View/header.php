<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300" rel="stylesheet" type="text/css">
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
    min-height: 100vh;
    padding: 0;
    margin: 0;
}

h1, h2, h3 {
    font-weight: 600;
}

html {
    font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
    font-size: 16px;
    color: #F7F6F6;
    background: #262833;
    background: linear-gradient( 135deg, #262833 10%, #101015 100%);
}

body {
    min-width: 300px;
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

#intro, #error, #examples {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

#intro, #error {
    height: calc(100vh - 66px);
}

#examples {
    padding: 5rem 2rem 2rem 2rem;
}

#intro > header, #error > header {
    text-align: center;
    padding-bottom: 1rem;
}

#intro h1, #examples h1 {
    font-size: 9.5rem;
    font-weight: bold;
    background: linear-gradient(135deg, #FD6E6A 10%, #FFC600 100%);
    background-clip: text;
    text-fill-color: transparent;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

#examples h1 {
    font-size: 4.2rem;
}

#error h1 {
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
}

#links > a {
    transition: .2s all ease;
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

#items {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    gap: 2rem;
    padding: 2rem;
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
    padding: 1rem;
    font-weight: bold;
}

#items > dl > dd {
    border-top: thin solid rgba(255,255,255,.2);
}

#items > dl > dd > a {
    color: inherit;
    display: block;
    padding: 1rem;
    text-decoration: none;
    transition: .3s all ease;
}

#items > dl > dd > a:hover,
#items > dl > dd > a:active,
#items > dl > dd > a:focus {
    background: rgba(255,255,255,.1);
}

@media (max-width: 410px) {
    main > header {
        justify-content: center;
    }
}

@media (max-width: 890px) {
    html {
        font-size: 10px;
    }
}

.example-list {

}
</style>
