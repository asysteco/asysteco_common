<?php

// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

date_default_timezone_set('Europe/Madrid');

// Requerimos fichero de configuración esencial de directorios y constantes
require_once("./horario.php");
require_once("./config.php");
require_once("./dirs.php");
require_once("./initial_vars.php");

// Requerimos el fichero de configuración de variables de conexión
require_once($dirs['bdConfig']);

// Requerimos la clase Asysteco
require_once($dirs['class'] . 'Asysteco.php');

// iniciamos la clase y la guardamos en $class
$class = new Asysteco;

// Iniciamos la conexión a la base de datos
try {
  if (!$class->bdConex($insti_host, $insti_user, $insti_pass, $insti_db)) {
    throw new Exception($class->ERR_ASYSTECO);
  }

  // Establecemos UTF8 como cotejamiento de caracteres
  if (!$class->conex->set_charset("utf8")) {
    throw new Exception('Error al conectar con el servicio, inténtelo más tarde o contacte con los administradores.');
  }
} catch (Exception $e) {
  echo $e->getMessage();
  exit;
}

// Comprobamos si existen horarios para actualizar
if (!$class->horarioTemporalAHorarioReal()) {
  $ERR_MSG = $class->ERR_ASYSTECO;
  $ERR_MSG .= "
    <br>
    <span class='glyphicon glyphicon-warning-sign'> </span> Contacta urgentemente con los administradores de la plataforma.
    <br>
    <a href='mailto:admin@asysteco.com?subject=Urgente%20ASYSTECO%20Horarios_Temporales&body=Ha%20surgido%20un%20problema%20al%20generar%20los%20horarios%20desde%20temporales%20en%20$Titulo.'>Enviar correo urgente</a>";
}

// Comprobamos si está seteada la variable ACTION en la URL (Método GET)

if (isset($_GET['ACTION'])) {
  if ($_GET['ACTION'] === 'logout') {
    require_once($dirs['Login'] . 'logout.php');
  }

  if ($_GET['ACTION'] === 'cp') {
    require_once($dirs['CP'] . 'IndexCase.php');
  }

  if (!$class->isLogged($Titulo)) {
    require_once($dirs['Login'] . 'IndexCase.php');
    return;
  }

  if (!$class->compruebaCambioPass()) {
    require_once($dirs['FirstChangePass'] . 'IndexCase.php');
    return;
  }

  switch ($_GET['ACTION']) {
    default:
      require_once($dirs['Login'] . 'IndexCase.php');
      break;

    case 'admin-login':
      require_once($dirs['Login'] . 'admin-login.php');
      break;

    case 'cambio_pass':
      require_once($dirs['ChangePass'] . 'IndexCase.php');
      break;

    case 'lectivos':
      require_once($dirs['Lectivos'] . 'IndexCase.php');
      break;

    case 'qrcoder':
      require_once($dirs['Qr'] . 'IndexCase.php');
      break;

    case 'horarios':
      require_once($dirs['Horarios'] . 'IndexCase.php');
    break;

    case 'asistencias':
      require_once($dirs['Asistencias'] . 'IndexCase.php');
      break;

    case 'profesores':
      require_once($dirs['Profesores'] . 'IndexCase.php');
      break;

    case 'marcajes':
      require_once($dirs['Marcajes'] . 'IndexCase.php');
      break;

    case 'guardias':
      require_once($dirs['Guardias'] . 'IndexCase.php');
      break;

    case 'notificaciones':
      require_once($dirs['Notificaciones'] . 'IndexCase.php');
      break;

    case 'admon':
      require_once($dirs['Admon'] . 'IndexCase.php');
      break;

    case 'fichar-qr':
      require_once($dirs['FicharQr'] . 'IndexCase.php');
      break;

    case 'fichar-manual':
      require_once($dirs['FicharManual'] . 'IndexCase.php');
      break;
    
    case 'download':
      require_once($dirs['Downloads'] . 'IndexCase.php');
    break;

    case 'clean_tmp':
      include_once($dirs['Helper'] . 'clean_tmp.php');
      break;
  }
} else {
  if (isset($_SESSION['logged']) && $_SESSION['logged'] === true && !$class->compruebaCambioPass()) {
    require_once($dirs['FirstChangePass'] . 'IndexCase.php');
    return;
  }
  require_once($dirs['Login'] . 'IndexCase.php');
}
