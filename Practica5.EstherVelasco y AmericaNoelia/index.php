<?php


    session_start(); //iniciar la sesión

    if (isset($_GET['reiniciar'])){ //reiniciar la sesion
    session_destroy();
    session_start();
     }


    //mi array de provincias con nombre y código que tengo guardado en otro documento de texto
    $provincias = [];  //array de provincias 
    $string = file_get_contents("archivo.txt"); 
    $array = explode("\n",$string); //por cada salta de linea divido la cadena es un array
    foreach ($array as $fila){
        $item = explode(" ",$fila);//por cada espacio
        $provincias[] = [
            'numero' => $item[0], 
            'nombre' => $item[1]
        ];
    }
      
    if(isset($_POST['enviar'])){ //cuando pulse enviar se ejecutarán las funciones siguientes
        
            function letras(){ //NOMBRE , APELLIDOS Y CIUDAD 
            $nombre=filter_input(INPUT_POST,'nombre',FILTER_SANITIZE_STRING);
            $apellidos=filter_input(INPUT_POST,'apellidos',FILTER_SANITIZE_STRING);
            $ciudad= filter_input(INPUT_POST,'ciudad',FILTER_SANITIZE_STRING);
            $regex="/[0-9]/";
            $valorA=preg_match($regex,$apellidos);
            $valorN=preg_match($regex,$nombre);
            $valorC=preg_match($regex,$ciudad);
            if($valorA==1 || $valorN==1 || $valorC==1){ //si alguno contiene un número sale el mensaje
                echo "<p>No puedes introducir un número en el campo apellido, nombre o ciudad <p/>";
            }if($nombre==null){
                echo "<p>Debes introducir un nombre</p>";
            }if($apellidos==null){
                echo "<p>Debes introducir un apellido</p>";
            }if($ciudad==null){
                echo "<p>Debes introducir una ciudad</p>";
            }
        }
        letras();
        
         $provincia=$_POST['provincia'];//recogemos la provincia seleccionada , tendremos como valor el código (2 primeros números)
        //para comprobar que el código postal coincide con la provincia 
        //utilizaremos substr para tomar los dos primeros digitos del codigo postal introducido
        function codigoPValido(){
            $codigoP=filter_input(INPUT_POST,'codigoP',FILTER_SANITIZE_STRING);
            $provincia=$_POST['provincia'];
            $misDigitos= substr($codigoP,0,-3);  //toma los números empezando por el primero restando los últimos 3 , es decir toma los dos primeros
            if($codigoP==null){
                echo "<p>Debes introducir la provincia </p>";
            }else if($misDigitos!==$provincia){
                echo "<p>El código postal es erróneo</p>";
            }
        }
        codigoPValido();
        
       
        function codigo(){
            $codigoP=filter_input(INPUT_POST,'codigoP',FILTER_SANITIZE_STRING);
            $codigoTlf=filter_input(INPUT_POST,'telefono',FILTER_SANITIZE_STRING);
            $regexCodigo="/(^([0-9]{5,5})|^)$/"; //tienen que ser 5 números para el CODIGO POSTAL
            $regexCodigoTlf="/(^([0-9]{9,9})|^)$/"; //para el teléfono 9 numeros
            $valorC=preg_match($regexCodigo,$codigoP);
            $valorTlf=preg_match($regexCodigoTlf,$codigoTlf);
            
            if($valorC==1 && $valorTlf==1){//si el codigo postal y el teléfono están bien no pasa nada 

            }else if($valorC==0 && $valorTlf==0){
                echo "<p>El teléfono y el código postal son erróneos.<p/>";
            }else if($valorC==0){
                echo "<p>El código postal es erróneo.Deben ser 5 números.</p>";
            }else if($valorTlf==0){
                echo "<p>El teléfono es erróneo.Deben ser 9 números.</p>";
            }if($codigoP==null){ //si el campo está vacío
                echo "<p>Debes introducir el codigo postal</P>"; 
            }if($codigoTlf==null){
                echo "<p>Debes introducir el teléfono</P>";
            }
        }
        codigo(); //llamamos a la función 
        
        
        
        
        function email(){//EMAIL
            $email= filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);//elimina todos los caracteres ilegales de una dirección de correo electrónico
            //además si no hay un @ sale una advertencia 
            $regex="/^.+@.+\..+$/"; //al principio debe llevar un @ y al final un punto
            $valorE= preg_match($regex, $email);
            if($email==null){
                echo "<p>Debes introducir un email</p>";
            }else if($valorE==0){
                echo "<p>El email es incorrecto</p>";
        }
        }
        email();
        
         function contraseña(){
            $contraseña= filter_input(INPUT_POST,'contraseña',FILTER_SANITIZE_STRING);
            $regex = "/((?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^A-Za-z0-9])).{8,12}/";
            //la contraseña contendra letras minúsculas, mayúsculas , un carácter especial , algún número y deberá ocupar entre 8 y 12 carácteres 
            $valor= preg_match($regex, $contraseña);

            if($contraseña==null){
                echo "<p>Debes introducir la contraseña</p>"; 
            }else if($valor==0){
                echo "<p>La contraseña es incorrecta </p>";
        }
        }
        contraseña();
        
        function web(){
            $web= filter_input(INPUT_POST,'web',FILTER_SANITIZE_URL);//elimina todos los caracteres ilegales de una dirección de una url
            $regex="/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|](\.)[a-z]{2}/i";
            //https://didesweb.com/tutoriales-php/validar-url-php/
            //la web introducida tendrá que empezar por https o ftp o www
            $valorW= preg_match($regex, $web);
            
            if($web==null){
                echo "<p>Debes introducir una web</p>";
            }else if($valorW==0){
                echo "<p>La dirección no es válida</p>";
        }
        }
         web();
        
        }
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Formulario de registro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Formulario de registro</h1>
<form action="index.php" method="POST">
    <div class="formulario">
        <label>Nombre: </label><input type="text" name="nombre" placeholder="Introduce tu nombre" title="Solo puedes introducir letras"><br><br>
    <label>Apellidos: </label><input type="text" name="apellidos" placeholder="Introduce tu apellido" title="Solo puedes introducir letras"><br><br>
    <label>Dirección: </label><input type="text" name="direccion" placeholder="Introduce tu dirección" ><br><br>
    <label>Ciudad: </label><input type="text" name="ciudad" placeholder="Introduce tu ciudad" title="Solo puedes introducir letras"><br><br>
    <label>Provincia: </label><select name="provincia">
	<?php // select de provincias , guardamos el número como value
	for($i=0;$i<count($provincias);$i++){
                echo '<option value='.$provincias[$i]['numero'].'>'.$provincias[$i]['nombre'].'</option>';
            }
         ?>
    </select><br><br>
    <label>Código postal: </label><input type="text" name="codigoP" placeholder="Introduce tu código postal" title="Debe contener 5 números"><br><br>
    <label>Teléfono: </label><input type="text" name="telefono" placeholder="Introduce tu teléfono" title="Debe contener 9 números"><br><br>
    <label>Email: </label><input type="email" name="email" placeholder="Introduce tu email"><br><br>
    <label>Contraseña: </label><input type="text" name="contraseña" placeholder="Introduce tu contraseña" ><br><br>
      <label>Web: </label><input type="text" name="web" placeholder="Introduce tu web"><br><br>
    <input class="botón" type="submit" name="enviar" value="enviar"><br><br>
    <div/>
</form>
<input class="boton" type="submit" name="reiniciar" value="Reiniciar">
</body>
</html>
     
