<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$nome = $endereco = $salario = "";
$name_err = $address_err = $salary_err = "";
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
// Get hidden input value
$id = $_POST["id"];
// Validate name
$input_name = trim($_POST["nome"]);
if(empty($input_name)){
$name_err = "Digite um nome.";
} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP,
array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
$name_err = "Entre um com nome válido.";
} else{
$nome = $input_name;
}
// Validate address address
$input_address = trim($_POST["endereco"]);
if(empty($input_address)){
$address_err = "Digite um endereço.";
} else{
$address = $input_address;
}
// Validate salary
$input_salary = trim($_POST["salario"]);
if(empty($input_salary)){
$salary_err = "Entre um valor de salário.";
} elseif(!ctype_digit($input_salary)){
$salary_err = "Entre com um valor inteiro positivo.";
} else{
$salario = $input_salary;
}

// Check input errors before inserting in database
if(empty($name_err) && empty($address_err) && empty($salary_err)){
    // Prepare an update statement
    $sql = "UPDATE employees SET name=:nome, address=:endereco,
    salary=:salario WHERE id=:id";
    if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":nome", $param_name);
    $stmt->bindParam(":endereco", $param_address);
    $stmt->bindParam(":salario", $param_salary);
    $stmt->bindParam(":id", $param_id);
    // Set parameters
    $param_name = $nome;
    $param_address = $endereco;
    $param_salary = $salario;
    $param_id = $id;
    // Attempt to execute the prepared statement
    if($stmt->execute()){
    // Records updated successfully. Redirect to landing page
    header("location: index.php");
    exit();
    } else{
    echo "Algo deu errado. Por favor, tente mais tarde.";
    }
    }
    // Close statement
    unset($stmt);
    }
    // Close connection
    unset($pdo);
    } else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id = trim($_GET["id"]);
    // Prepare a select statement
    $sql = "SELECT * FROM employees WHERE id = :id";
    if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":id", $param_id);
    // Set parameters
    $param_id = $id;
    // Attempt to execute the prepared statement
    if($stmt->execute()){
    if($stmt->rowCount() == 1){
    
    /* Fetch result row as an associative array. Since the
result set contains only one row, we don't need to use while loop */
$row = $stmt->fetch(PDO::FETCH_ASSOC);
// Retrieve individual field value

$nome = $row["nome"];
$endereco = $row["endereco"];
$salario = $row["salario"];
} else{
// URL doesn't contain valid id. Redirect to error page

header("location: error.php");
exit();

}
} else{
echo "Oops! Algo deu errado. Por favor, tente mais tarde.";
}
}
// Close statement
unset($stmt);
// Close connection
unset($pdo);
} else{
// URL doesn't contain id parameter. Redirect to error page
header("location: error.php");
exit();
}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Atualizar Registro</title>
<link rel="stylesheet"
href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
<style>
.wrapper{
width: 500px;
margin: 0 auto;
}
</style>
</head>
<body>
<div class="wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div class="page-header">
<h2>Atualizar Registro</h2>

</div>

<p>Por favor, altere os valores de entrada e clique no botão

ENVIAR para atualizar o registro.</p>
<form action="<?php echo
htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
<div class="form-group <?php echo (!empty($name_err)) ?
'has-error' : ''; ?>">
<label>Nome</label>
<input type="text" name="nome" class="form-control"
value="<?php echo $nome; ?>">
<span class="help-block"><?php echo
$name_err;?></span>
</div>

<div class="form-group <?php echo (!empty($address_err))

? 'has-error' : ''; ?>">
<label>Endereço</label>
<textarea name="endereco" class="form-control"><?php
echo $endereco; ?></textarea>
<span class="help-block"><?php echo
$address_err;?></span>
</div>

<div class="form-group <?php echo (!empty($salary_err))

? 'has-error' : ''; ?>">
<label>Salário</label>

<input type="text" name="salario" class="form-
control" value="<?php echo $salario; ?>">

<span class="help-block"><?php echo
$salary_err;?></span>
</div>

<input type="hidden" name="id" value="<?php echo $id;

?>"/>
<input type="submit" class="btn btn-primary"
value="Enviar">
<a href="index.php" class="btn btn-default">Cancelar</a>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
