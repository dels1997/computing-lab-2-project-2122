<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>freeDiving</title>
    <link rel="stylesheet" href="<?php echo __SITE_URL;?>/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@200&display=swap" rel="stylesheet"> -->
</head>
<body>
    <!-- <h1>freeDiving</h1> -->

    <nav class="navbar">
    <ul class="navbar-nav">
      <li class="logo">
        <a href="freediving.php?rt=tables/index" class="nav-link">
          <span class="link-text logo-text">freeDiving</span>
          <svg
            aria-hidden="true"
            focusable="false"
            data-prefix="fad"
            data-icon="angle-double-right"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 448 512"
            class="svg-inline--fa fa-angle-double-right fa-w-14 fa-5x"
          >
            <g class="fa-group">
              <path
                fill="currentColor"
                d="M224 273L88.37 409a23.78 23.78 0 0 1-33.8 0L32 386.36a23.94 23.94 0 0 1 0-33.89l96.13-96.37L32 159.73a23.94 23.94 0 0 1 0-33.89l22.44-22.79a23.78 23.78 0 0 1 33.8 0L223.88 239a23.94 23.94 0 0 1 .1 34z"
                class="fa-secondary"
              ></path>
              <path
                fill="currentColor"
                d="M415.89 273L280.34 409a23.77 23.77 0 0 1-33.79 0L224 386.26a23.94 23.94 0 0 1 0-33.89L320.11 256l-96-96.47a23.94 23.94 0 0 1 0-33.89l22.52-22.59a23.77 23.77 0 0 1 33.79 0L416 239a24 24 0 0 1-.11 34z"
                class="fa-primary"
              ></path>
            </g>
          </svg>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=tables/index" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" class="fa-primary" d="M448 32C483.3 32 512 60.65 512 96V416C512 451.3 483.3 480 448 480H64C28.65 480 0 451.3 0 416V96C0 60.65 28.65 32 64 32H448zM224 256V160H64V256H224zM64 320V416H224V320H64zM288 416H448V320H288V416zM448 256V160H288V256H448z"/>
        </svg>
          <span class="link-text">Traning history</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=tables/start" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
          <path fill="currentColor" class="fa-primary" d="M272 0C289.7 0 304 14.33 304 32C304 49.67 289.7 64 272 64H256V98.45C293.5 104.2 327.7 120 355.7 143L377.4 121.4C389.9 108.9 410.1 108.9 422.6 121.4C435.1 133.9 435.1 154.1 422.6 166.6L398.5 190.8C419.7 223.3 432 262.2 432 304C432 418.9 338.9 512 224 512C109.1 512 16 418.9 16 304C16 200 92.32 113.8 192 98.45V64H176C158.3 64 144 49.67 144 32C144 14.33 158.3 0 176 0L272 0zM248 192C248 178.7 237.3 168 224 168C210.7 168 200 178.7 200 192V320C200 333.3 210.7 344 224 344C237.3 344 248 333.3 248 320V192z"/>
        </svg>
          <span class="link-text">Start a new table</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=tables/edit" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" class="fa-primary" d="M362.7 19.32C387.7-5.678 428.3-5.678 453.3 19.32L492.7 58.75C517.7 83.74 517.7 124.3 492.7 149.3L444.3 197.7L314.3 67.72L362.7 19.32zM421.7 220.3L188.5 453.4C178.1 463.8 165.2 471.5 151.1 475.6L30.77 511C22.35 513.5 13.24 511.2 7.03 504.1C.8198 498.8-1.502 489.7 .976 481.2L36.37 360.9C40.53 346.8 48.16 333.9 58.57 323.5L291.7 90.34L421.7 220.3z"/></svg>
          <span class="link-text">Edit my tables</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=tables/onebreath" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" class="fa-primary" d="M640 419.8c0 61.25-62.5 105.5-125.3 88.63l-59.53-15.88c-42.12-11.38-71.25-47.5-71.25-88.63L384 316.4l85.88 57.25c3.625 2.375 8.625 1.375 11-2.25l8.875-13.37c2.5-3.625 1.5-8.625-2.125-11L320 235.3l-167.6 111.8c-1.75 1.125-3 3-3.375 5c-.375 2.125 0 4.25 1.25 6l8.875 13.37c1.125 1.75 3 3 5 3.375c2.125 .375 4.25 0 6-1.125L256 316.4l.0313 87.5c0 41.13-29.12 77.25-71.25 88.63l-59.53 15.88C62.5 525.3 0 481 0 419.8c0-10 1.25-19.88 3.875-29.63C25.5 308.9 59.91 231 105.9 159.1c22.12-34.63 36.12-63.13 80.12-63.13C224.7 96 256 125.4 256 161.8v60.1l32.88-21.97C293.4 196.9 296 192 296 186.6V16C296 7.125 303.1 0 312 0h16c8.875 0 16 7.125 16 16v170.6c0 5.375 2.625 10.25 7.125 13.25L384 221.8v-60.1c0-36.38 31.34-65.75 69.97-65.75c43.1 0 58 28.5 80.13 63.13c46 71.88 80.41 149.8 102 231C638.8 399.9 640 409.8 640 419.8z"/></svg>
          <span class="link-text">One breath mode</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=webshop/index" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" class="fa-primary" d="M171.7 191.1H404.3L322.7 35.07C316.6 23.31 321.2 8.821 332.9 2.706C344.7-3.409 359.2 1.167 365.3 12.93L458.4 191.1H544C561.7 191.1 576 206.3 576 223.1C576 241.7 561.7 255.1 544 255.1L492.1 463.5C484.1 492 459.4 512 430 512H145.1C116.6 512 91 492 83.88 463.5L32 255.1C14.33 255.1 0 241.7 0 223.1C0 206.3 14.33 191.1 32 191.1H117.6L210.7 12.93C216.8 1.167 231.3-3.409 243.1 2.706C254.8 8.821 259.4 23.31 253.3 35.07L171.7 191.1zM191.1 303.1C191.1 295.1 184.8 287.1 175.1 287.1C167.2 287.1 159.1 295.1 159.1 303.1V399.1C159.1 408.8 167.2 415.1 175.1 415.1C184.8 415.1 191.1 408.8 191.1 399.1V303.1zM271.1 303.1V399.1C271.1 408.8 279.2 415.1 287.1 415.1C296.8 415.1 304 408.8 304 399.1V303.1C304 295.1 296.8 287.1 287.1 287.1C279.2 287.1 271.1 295.1 271.1 303.1zM416 303.1C416 295.1 408.8 287.1 400 287.1C391.2 287.1 384 295.1 384 303.1V399.1C384 408.8 391.2 415.1 400 415.1C408.8 415.1 416 408.8 416 399.1V303.1z"/></svg>          <span class="link-text">Check out products</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="freediving.php?rt=webshop/add" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" class="fa-primary" d="M96 0C107.5 0 117.4 8.19 119.6 19.51L121.1 32H541.8C562.1 32 578.3 52.25 572.6 72.66L518.6 264.7C514.7 278.5 502.1 288 487.8 288H170.7L179.9 336H488C501.3 336 512 346.7 512 360C512 373.3 501.3 384 488 384H159.1C148.5 384 138.6 375.8 136.4 364.5L76.14 48H24C10.75 48 0 37.25 0 24C0 10.75 10.75 0 24 0H96zM272 180H316V224C316 235 324.1 244 336 244C347 244 356 235 356 224V180H400C411 180 420 171 420 160C420 148.1 411 140 400 140H356V96C356 84.95 347 76 336 76C324.1 76 316 84.95 316 96V140H272C260.1 140 252 148.1 252 160C252 171 260.1 180 272 180zM128 464C128 437.5 149.5 416 176 416C202.5 416 224 437.5 224 464C224 490.5 202.5 512 176 512C149.5 512 128 490.5 128 464zM512 464C512 490.5 490.5 512 464 512C437.5 512 416 490.5 416 464C416 437.5 437.5 416 464 416C490.5 416 512 437.5 512 464z"/></svg>          <span class="link-text">Add product</span>
        </a>
      </li>

      <?php
        if($_SESSION['admin'])
        {
          echo '<li class="nav-item">
              <a href="freediving.php?rt=users/index" class="nav-link">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M319.9 320c57.41 0 103.1-46.56 103.1-104c0-57.44-46.54-104-103.1-104c-57.41 0-103.1 46.56-103.1 104C215.9 273.4 262.5 320 319.9 320zM369.9 352H270.1C191.6 352 128 411.7 128 485.3C128 500.1 140.7 512 156.4 512h327.2C499.3 512 512 500.1 512 485.3C512 411.7 448.4 352 369.9 352zM512 160c44.18 0 80-35.82 80-80S556.2 0 512 0c-44.18 0-80 35.82-80 80S467.8 160 512 160zM183.9 216c0-5.449 .9824-10.63 1.609-15.91C174.6 194.1 162.6 192 149.9 192H88.08C39.44 192 0 233.8 0 285.3C0 295.6 7.887 304 17.62 304h199.5C196.7 280.2 183.9 249.7 183.9 216zM128 160c44.18 0 80-35.82 80-80S172.2 0 128 0C83.82 0 48 35.82 48 80S83.82 160 128 160zM551.9 192h-61.84c-12.8 0-24.88 3.037-35.86 8.24C454.8 205.5 455.8 210.6 455.8 216c0 33.71-12.78 64.21-33.16 88h199.7C632.1 304 640 295.6 640 285.3C640 233.8 600.6 192 551.9 192z"/></svg>
                <span class="link-text">User control</span>
              </a>
            </li>';
        }
      ?>
      <li class="nav-item" id="themeButton">
        <a href="freediving.php?rt=login/index" class="nav-link">
          <svg
            class="theme-icon"
            id="lightIcon"
            aria-hidden="true"
            focusable="false"
            data-prefix="fad"
            data-icon="moon-stars"
            role="img"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
            class="svg-inline--fa fa-moon-stars fa-w-16 fa-7x"
          >
            <g class="fa-group">
              <path
                fill="currentColor"
                d="M320 32L304 0l-16 32-32 16 32 16 16 32 16-32 32-16zm138.7 149.3L432 128l-26.7 53.3L352 208l53.3 26.7L432 288l26.7-53.3L512 208z"
                class="fa-secondary"
              ></path>
              <path
                fill="currentColor"
                d="M332.2 426.4c8.1-1.6 13.9 8 8.6 14.5a191.18 191.18 0 0 1-149 71.1C85.8 512 0 426 0 320c0-120 108.7-210.6 227-188.8 8.2 1.6 10.1 12.6 2.8 16.7a150.3 150.3 0 0 0-76.1 130.8c0 94 85.4 165.4 178.5 147.7z"
                class="fa-primary"
              ></path>
            </g>
          </svg>
          <span class="link-text">Log out</span>
        </a>
      </li>
    </ul>
  </nav>

    <main>