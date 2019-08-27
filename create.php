<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$nome = $endereco = $salario = "";
$name_err = $address_err = $salary_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
// Validate name
$input_name = trim($_POST["nome"]);
if(empty($input_name)){
$name_err = "Digite um nome.";
} elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP,
array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
$name_err = "Digite um nome válido.";
} else{
$nome = $input_name;
}
// Validate address
$input_address = trim($_POST["endereco"]);
if(empty($input_address)){
$address_err = "Digite um endereço.";
} else{
$endereco = $input_address;
}
// Validate salary
$input_salary = trim($_POST["salario"]);
if(empty($input_salary)){
$salary_err = "Entre com um valor numérico.";
} elseif(!ctype_digit($input_salary)){
$salary_err = "Digite um valor inteiro.";
} else{
$salario = $input_salary;
}
// Check input errors before inserting in database
if(empty($name_err) && empty($address_err) && empty($salary_err)){
// Prepare an insert statement
$sql = "INSERT INTO employees (nome, endereco, salario) VALUES (:nome,
:endereco, :salario)";
if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":nome", $param_name);
    $stmt->bindParam(":endereco", $param_address);
    $stmt->bindParam(":salario", $param_salary);
    // Set parameters
    $param_name = $nome;
    $param_address = $endereco;
    $param_salary = $salario;
    // Attempt to execute the prepared statement
    if($stmt->execute()){
    // Registros criados com sucesso. Redirect to landing page
    header("location: index.php");
    exit();
    } else{
    echo "Algo deu errado. Por favor, tente novamente mais tarde.";
    }
    }
    // Close statement
    unset($stmt);
    }
    // Close connection
    unset($pdo);
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Criar Registro</title>
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
    <h2>Criar Registro</h2>
    </div>
    <p>Please fill this form and submit to add employee record
to the database.</p>
<form action="<?php echo
htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="form-group <?php echo (!empty($name_err)) ?
'has-error' : ''; ?>">
<label>Nome</label>
<input type="text" name="nome" class="form-control"
value="<?php echo $name; ?>">
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