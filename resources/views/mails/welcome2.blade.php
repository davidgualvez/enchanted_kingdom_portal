<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
   <!-- Bootstrap core CSS -->
    <style>
      @media screen {
        @font-face {
          font-family: 'Lato';
          font-style: normal;
          font-weight: 400;
          src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
        } 
    }
      body {
        font-family: "Lato", "Lucida Grande", "Lucida Sans Unicode", Tahoma, Sans-Serif;
      }
      table {
        border-collapse: collapse;
    }
      td, th {
        border: 1px solid gray;
        padding-left: 5px; 
        padding-right: 5px;
    }
    td.td-col{
      font-weight: bold;
    }
    </style>
</head>
<body>
  <div style="paddig:50px;">
      <h3>Good day! {{ ucfirst($name) }},</h3> 
      <p>
        Welcome to Enchanted Kingdom! You are now a Loyalty Member. You may now load up your wallet to purchase anything from the store and earn a points for every purchase. Enjoy!
      </p>
  </div>
  
</body>
</html>