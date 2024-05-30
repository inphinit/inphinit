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
    height: 100%;
    padding: 0;
    margin: 0;
}

body {
    font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
    color: #5F5656;
    font-size: 16px;
    background: #F7F6F6;
    min-width: 300px;
}

main {
    display: -webkit-flex;
    display: flex;

    -webkit-flex-direction: column;
    flex-direction: column;

    width: 100%;
    height: 100%;

    padding: 1rem;
}

main h1, main h2 {
    padding: .4rem 0;
    margin: 0;
}

main h1 {
    font-size: 3.5rem;
    font-weight: 100;
    padding: .4rem 0;
    margin: 0;
}

main h2 {
    font-weight: normal;
    font-size: 1.5rem;
}

main > article {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

main > article > header {
    text-align: center;
    padding-bottom: 1rem;
}

main > footer {
    border-top: thin solid #F2EBEB;
    padding-top: 1rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: .2rem;
}

main > footer > a {
    transition: .2s all ease;
    text-decoration: none;
    display: block;
    padding: .4rem .8rem;
    color: inherit;
    border-radius: 1rem;
    background: transparent;
}

main > footer > a:hover {
    background: #eae2e2;
}

@media (max-width: 510px) {
    body {
        font-size: 14px;
    }

    main > footer {
        justify-content: center;
    }
}

@media (max-width: 410px) {
    main > footer {
        justify-content: center;
    }
}
</style>
