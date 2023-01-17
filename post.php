<?php
include "config.php";
include "utils.php";

$dbConn =  connect($db);

// SELECT 
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (isset($_GET['codigo']))
    {
      //Mostrar un post
      $sql = $dbConn->prepare("SELECT * registropersonas  where codigo=:codigo");
      $sql->bindValue(':codigo', $_GET['codigo']);
      $sql->execute();
      header("HTTP/1.1 200 OK");
      echo json_encode(  $sql->fetch(PDO::FETCH_ASSOC)  );
      exit();
    }

    else {
      //Mostrar lista de post
      $sql = $dbConn->prepare("SELECT * FROM registropersonas");
      $sql->execute();
      $sql->setFetchMode(PDO::FETCH_ASSOC);
      header("HTTP/1.1 200 OK");
      echo json_encode( $sql->fetchAll()  );
      exit();
  }
}

//INSERTAR
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = $_POST;
    $sql = "INSERT INTO registropersonas
          (Codigo,Nombre,Apellido,Telefono, Edad, Direccion)
          VALUES
          (:Codigo, :Nombre, :Apellido, :Telefono, :Edad, :Direccion)";
//    echo $sql;
    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    $postCodigo = $dbConn->lastInsertId();
    if($postCodigo)
    {
      $input['codigo'] = $postCodigo;
      header("HTTP/1.1 200 OK");
      echo json_encode($input);
      exit();
   }
}

//Eliminar
if ($_SERVER['REQUEST_METHOD'] == 'DELETE')
{
  $codigo = $_GET['Codigo'];
  $statement = $dbConn->prepare("DELETE FROM  registropersonas where codigo=:codigo");
  $statement->bindValue(':codigo', $codigo);
  $statement->execute();
  header("HTTP/1.1 200 OK");
  exit();
}

//Actualizar

if ($_SERVER['REQUEST_METHOD'] == 'PUT')
{
    $input = $_GET;
    $postCodigo = $input['Codigo'];
    $fields = getParams($input);

    $sql = "
          UPDATE registropersonas
          SET $fields
          WHERE codigo='$postCodigo'
           ";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);

    $statement->execute();
    header("HTTP/1.1 200 OK");
    exit();
}
//En caso de que ninguna de las opciones anteriores se haya ejecutado



?>