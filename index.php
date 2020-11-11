<?php

// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

date_default_timezone_set('Europe/Madrid');
// Requerimos fichero de configuración esencial de directorios y constantes
require_once("./config.php");

// Requerimos el fichero de configuración de variables de conexión
require_once($dirs['bdConfig']);

// Requerimos la clase Asysteco
require_once($dirs['class'] . 'Asysteco.php');

// iniciamos la clase y la guardamos en $class
$class = new Asysteco;

// Iniciamos la conexión a la base de datos
$class->bdConex($insti_host, $insti_user, $insti_pass, $insti_db);

// Establecemos UTF8 como cotejamiento de caracteres
if (!$class->conex->set_charset("utf8")) {
  printf("Error cargando el conjunto de caracteres utf8: %s\n", $class->conex->error);
  exit();
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
// Si no es así, procedemos a validar el login, si este es correcto cargamos el fichero home.php
// En su defecto cargaremos el formulario de login

if (isset($_GET['ACTION'])) {
  switch ($_GET['ACTION']) {
    default:
      if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
        require_once($dirs['inc'] . 'login_valida.php');
      }
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if ($_SESSION['Perfil'] === 'Admin') {
            $act_home = 'active';
            $scripts = '<link rel="stylesheet" href="css/profesores.css">';
            if (isset($_POST['boton']) && $class->validRegisterProf()) {
              header('Location: index.php?ACTION=profesores');
            }
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'profesores.php');
            include($dirs['inc'] . 'errors.php');
            include($dirs['inc'] . 'footer.php');
          } elseif ($_SESSION['Perfil'] === 'Profesor') {
            $act_qr = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'generate_code.php');
            include($dirs['inc'] . 'errors.php');
            include($dirs['inc'] . 'footer.php');
          } else {
            die('<h1 style="color:red;">Error de proceso...</h1>');
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        include_once($dirs['inc'] . 'login_form.php');
      }
      break;

    case 'admin-login':
      include_once($dirs['inc'] . 'admin-login.php');
      break;

    case 'logout':
      include_once($dirs['inc'] . 'logout.php');
      break;

    case 'pruebas':
      $scripts = '<link rel="stylesheet" href="css/estilos.css">';
      include_once($dirs['inc'] . 'top-nav.php');
      include_once($dirs['inc'] . 'pruebas.php');
      include_once($dirs['inc'] . 'errors.php');
      include_once($dirs['inc'] . 'footer.php');
      break;

    case 'cambio_pass':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $scripts = '<link rel="stylesheet" href="css/login-style.css">';
          include_once($dirs['inc'] . 'valida_new_pass.php');
          include_once($dirs['inc'] . 'top-nav.php');
          include_once($dirs['inc'] . 'new_pass.php');
          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'primer_cambio':
      $scripts = '<link rel="stylesheet" href="css/login-style.css">';
      include_once($dirs['inc'] . 'valida_primer_cambio.php');
      include_once($dirs['inc'] . 'top-nav.php');
      include_once($dirs['inc'] . 'primer_cambio.php');
      include_once($dirs['inc'] . 'errors.php');
      include_once($dirs['inc'] . 'footer.php');
      break;

    case 'lectivos':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if ($response = $class->query("SELECT COUNT(*) as num FROM $class->marcajes")) {
            $act_cal_escolar = 'active';
            $marcajes = $response->fetch_assoc();
            if ($marcajes['num'] > 0) {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              include_once($dirs['inc'] . 'top-nav.php');
              echo '<div class="container" style="margin-top: 50px;">';
              echo "<div class='row'>";
              echo "<div class='col-xs-12'>";
              include_once($dirs['inc'] . 'calendario.php');
              echo "</div>";
              echo "</div>";
              echo "</div>";
              include_once($dirs['inc'] . 'errors.php');
              include_once($dirs['inc'] . 'footer.php');
            } else {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              $extras = "
                  $(function (){
                      $('#datepicker_ini').datepicker();
                  });
                  $(function (){
                      $('#datepicker_fin').datepicker();
                  });
                  $(function (){
                      $('#datepicker_ini_fest').datepicker();
                  });
                  $(function (){
                      $('#datepicker_fin_fest').datepicker();
                  });
                ";
              include_once($dirs['inc'] . 'valida-lectivos.php');
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'lectivos.php');
              include_once($dirs['public'] . 'js/lectivos.js');
              include_once($dirs['inc'] . 'errors.php');
              include_once($dirs['inc'] . 'footer.php');
            }
          } else {
            $ERR_MSG = $class->ERR_ASYSTECO;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'qrcoder':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_qr = 'active';
          include_once($dirs['inc'] . 'top-nav.php');
          include_once($dirs['inc'] . 'generate_code.php');
          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'registrarse':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if (isset($_POST['Nombre']) || isset($_POST['Iniciales']) || isset($_POST['pass1']) || isset($_POST['pass2'])) {
            include_once($dirs['inc'] . 'register_valida.php');
          } else {
            include_once($dirs['inc'] . 'register_form.php');
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'horarios':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_horario = 'active';

          switch ($_GET['OPT']) {
            case 'edit-horario':
                include_once($dirs['inc'] . 'Horarios/edit-horario.php');
              break;

            case 'gest-horario':
              $style = "
                #profesor, .add-fields {
                  text-align: center;
                  margin-bottom: 25px;
                }
                table {
                  text-align: center;
                  width: 80%;
                  margin: 5px;
                }
                table th {
                  text-align:center;
                }
                .remove {
                  padding: 10px;
                  transition-duration: 0.2s;
                }
                #update-btn {
                  position: fixed;
                  top: 75px;
                  right: 25px;
                  background-color: #5cb85ccf;
                }
                #update-btn:hover {
                  background-color: #449d44;
                }
                #cancel-btn {
                  position: fixed;
                  top: 75px;
                  left: 25px;
                  background-color: #d9534fc4;
                }
                #cancel-btn:hover {
                  background-color: #d9534f;
                }
              ";
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'Horarios/gest-horario.php');
              break;

            case 'crear':
              if ($_SESSION['Perfil'] == 'Admin') {
                $scripts = '<link rel="stylesheet" href="css/horarios-crear.css">';
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'crear-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'apply-now':
              if ($class->horarioTemporalAHorarioReal($_GET['fecha'])) {
                header("Location: index.php?ACTION=profesores");
              } else {
                $ERR_MSG = $class->ERR_ASYSTECO;
              }
            break;

            case 'cursos':
              if ($_SESSION['Perfil'] == 'Admin') {
                $style = "
                  table {
                    text-align: center;
                    width: 80%;
                    margin: 5px;
                  }
                  table th, table td {
                    text-align:center;
                    vertical-align: middle;
                  }
                  .remove, .edit {
                    padding: 10px;
                    transition-duration: 0.2s;
                  }
                ";
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'cursos.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
            break;

            case 'edit-cursos':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'edit-cursos.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
            break;

            case 'aulas':
              if ($_SESSION['Perfil'] == 'Admin') {
                $style = "
                  table {
                    text-align: center;
                    width: 80%;
                    margin: 5px;
                  }
                  table th, table td {
                    text-align:center;
                    vertical-align: middle;
                  }
                  .remove, .edit {
                    padding: 10px;
                    transition-duration: 0.2s;
                  }
                ";
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'aulas.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
            break;

            case 'edit-aulas':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'edit-aulas.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
            break;

            case 'cancel-changes':
              if ($_SESSION['Perfil'] == 'Admin') {
                if ($class->delHorarioTemporal($_GET['profesor'], $_GET['fecha'])) {
                  header("Location: index.php?ACTION=profesores");
                } else {
                  $ERR_MSG = $class->ERR_ASYSTECO;
                }
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'import-form':
              if ($_SESSION['Perfil'] == 'Admin') {
                $extras = "
                    $(function (){
                        $('#fecha_incorpora').datepicker({minDate: 0});
                    });
                  ";
                $style = "
                    input[type=file] {
                      display: inline-block;
                      padding: 6px 12px 6px 0;
                    }
                    .format-body {
                      margin-left: 25px;
                    }
                  ";
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'import-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'preview':
              if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['inc'] . 'preview-import-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'import-csv':
              if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['inc'] . 'import-mysql-horario-ajax.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'edit-horario-profesor':
              if ($_SESSION['Perfil'] == 'Admin') {
                $scripts = '<link rel="stylesheet" href="css/horarios-edit.css">';
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'edit-horario-profesor.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'edit-t':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'edit-t-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'update':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'actualiza.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'registros':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'muestra-registros-horarios.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'guardias':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'horario-guardias.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'edit-guardias':
              if ($_SESSION['Perfil'] == 'Admin') {
                if (isset($_GET['SUBOPT'])) {
                    include_once($dirs['inc'] . 'update-guardias.php');
                } else {
                  $scripts = '<link rel="stylesheet" href="css/horarios-edit-guardias.css">';
                  include_once($dirs['inc'] . 'top-nav.php');
                  include_once($dirs['inc'] . 'edit-guardias.php');
                }
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'profesor':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'horario-profesor.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'remove':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'remove-horario-profesor.php');
                if (isset($ERR_MSG) && $ERR_MSG != '') {
                  header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
                } else {
                  header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
                }
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'delete-all':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'delete_all_horarios.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            default:
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'horarios.php');
              break;
          }

          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'asistencias':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_asistencia = 'active';
          $scripts = '<link rel="stylesheet" href="css/asistencias.css">';
          $extras = "
              $(function (){
                  $('#busca_asiste').datepicker();
              });
            ";
          include_once($dirs['inc'] . 'top-nav.php');

          switch ($_GET['OPT']) {
            case 'all':
              if ($_SESSION['Perfil'] === 'Admin') {
                include_once($dirs['inc'] . 'contenido-asistencias-all.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            case 'sesion':
              $_GET['ID'] = $_SESSION['ID'];
              include_once($dirs['inc'] . 'contenido-asistencias.php');
              break;

            default:
              include_once($dirs['inc'] . 'contenido-asistencias.php');
              break;
          }

          include_once('js/filtro_asistencias.js');
          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }

      break;

    case 'profesores':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_profesores = 'active';
          switch ($_GET['OPT']) {
            case 'import-form':
              $style = "
                  input[type=file] {
                    display: inline-block;
                    padding: 6px 12px 6px 0;
                  }
                ";
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'import-profesorado.php');
              break;

            case 'preview':
              include_once($dirs['inc'] . 'preview-import-profesores.php');
              break;

            case 'import-csv':
              //require_once($dirs['inc'] . 'import-mysql-profesorado.php');
              require_once($dirs['inc'] . 'import-mysql-profesorado-ajax.php');
              break;

            case 'registros':
              include_once($dirs['inc'] . 'muestra-registros-profesores.php');
              break;

            case 'edit':
              $scripts = '<link rel="stylesheet" href="css/login-style.css">';
              $scripts .= '<link rel="stylesheet" href="css/profesores-edit.css">';
              include_once($dirs['inc'] . 'valida_edit_profesor.php');
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'editar_profesor.php');
              break;

            case 'sustituir':
              $scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'form_sustituto.php');
              break;

            case 'add-profesor':
              if (isset($_POST['add-profesor']) && $_POST['add-profesor'] === 'add') {
                if ($class->validRegisterProf()) {
                  $MSG = "Profesor: $_POST[Nombre] con iniciales: $_POST[Iniciales] añadido correctamente";
                  header('Refresh: 2; index.php?ACTION=profesores');
                  include_once($dirs['inc'] . 'top-nav.php');
                } else {
                  include_once($dirs['inc'] . 'top-nav.php');
                  include_once($dirs['inc'] . 'form-add-profesor.php');
                }
              } else {
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'form-add-profesor.php');
              }
              break;

            case 'add-sustituto':
              include_once($dirs['inc'] . 'agregar-sustituto.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'remove-sustituto':
              include_once($dirs['inc'] . 'retirar-sustituto.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'des-act':
              include_once($dirs['inc'] . 'des-act-profesor.php');
              if (isset($ERR_MSG) && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'reset-pass':
              include_once($dirs['inc'] . 'reset_pass.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'delete-all':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'delete_all_profesores.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'msg_modal.php');
              }
              break;

            default:
              $scripts = '<link rel="stylesheet" href="css/profesores.css">';
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'profesores.php');
              break;
          }

          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'marcajes':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          switch ($_GET['OPT']) {
            case 'update':
              include_once($dirs['inc'] . 'update-marcajes.php');
              break;

            default:
              header('Location: index.php');
              break;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'guardias':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_guardias = 'active';
          $scripts = '<link rel="stylesheet" href="css/qr-reader.css">';

          if (!$options['QR-reader']) {
            $scripts .= '
              <script type="text/javascript" src="js/jsqrcode/grid.js"></script>
              <script type="text/javascript" src="js/jsqrcode/version.js"></script>
              <script type="text/javascript" src="js/jsqrcode/detector.js"></script>
              <script type="text/javascript" src="js/jsqrcode/formatinf.js"></script>
              <script type="text/javascript" src="js/jsqrcode/errorlevel.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bitmat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datablock.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bmparser.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datamask.js"></script>
              <script type="text/javascript" src="js/jsqrcode/rsdecoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256poly.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256.js"></script>
              <script type="text/javascript" src="js/jsqrcode/decoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/qrcode.js"></script>
              <script type="text/javascript" src="js/jsqrcode/findpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/alignpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/databr.js"></script>
              ';
          }

          include_once($dirs['inc'] . 'top-nav.php');
          include($dirs['inc'] . 'home.php');
          include($dirs['inc'] . 'errors.php');
          include($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'cp':
      echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
          <title>Inicio</title>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
          <link rel="stylesheet" href="css/bootstrap-3.4.1/css/bootstrap.min.css">
          <link rel="stylesheet" href="css/asysteco.css">
          <link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
          <link rel="shortcut icon" href="resources/img/asysteco.ico" type="image/x-icon">
          <script src="js/jquery.min.js"></script>
          <script src="js/bootstrap.min.js"></script>
          <script src="js/jquery-ui/jquery-ui.min.js"></script>
          <script src="js/datepicker_common.js"></script>
          <script src="js/flecha.js"></script>
          <link rel="stylesheet" href="css/qr-reader.css">
          
          <script>
            var userAgent = navigator.userAgent.toLowerCase();
            var isSupportedBrowser = (/armv.* raspbian chromium/i).test(userAgent);
            if(! isSupportedBrowser)
            {
              location.href = "index.php";
            }
          </script>';

      if (!$options['QR-reader']) {
        echo '
              <script type="text/javascript" src="js/jsqrcode/grid.js"></script>
              <script type="text/javascript" src="js/jsqrcode/version.js"></script>
              <script type="text/javascript" src="js/jsqrcode/detector.js"></script>
              <script type="text/javascript" src="js/jsqrcode/formatinf.js"></script>
              <script type="text/javascript" src="js/jsqrcode/errorlevel.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bitmat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datablock.js"></script>
              <script type="text/javascript" src="js/jsqrcode/bmparser.js"></script>
              <script type="text/javascript" src="js/jsqrcode/datamask.js"></script>
              <script type="text/javascript" src="js/jsqrcode/rsdecoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256poly.js"></script>
              <script type="text/javascript" src="js/jsqrcode/gf256.js"></script>
              <script type="text/javascript" src="js/jsqrcode/decoder.js"></script>
              <script type="text/javascript" src="js/jsqrcode/qrcode.js"></script>
              <script type="text/javascript" src="js/jsqrcode/findpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/alignpat.js"></script>
              <script type="text/javascript" src="js/jsqrcode/databr.js"></script>';
        echo '</head>
            <body>';

        echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12'>";
        include($dirs['inc'] . 'qr-webcam-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-webcam-admin-login.js');
      } else {
        echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12' style='margin-top: 20vh;'>";
        include($dirs['inc'] . 'qr-reader-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-reader-admin-login.js');
      }
      include($dirs['inc'] . 'errors.php');
      include($dirs['inc'] . 'footer.php');
      break;

    case 'mensajes':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $scripts = '<link rel="stylesheet" href="css/mensajes.css">';
          $scripts .= '<link rel="stylesheet" href="css/message.css">';
          $extras = '
              $( function() {
                $( "#tabs" ).tabs();
              } );
            ';

          switch ($_GET['OPT']) {
            case 'add':
              include_once($dirs['inc'] . 'enviar_mensaje.php');
              break;

            case 'remove':
              include_once($dirs['inc'] . 'eliminar_mensaje.php');
              break;

            default:
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'form_mensajes.php');
              include_once($dirs['inc'] . 'listar_mensajes.php');
              include_once($dirs['public'] . 'js/menu_mensaje.js');
              break;
          }

          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'notificaciones':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          include_once($dirs['inc'] . 'top-nav.php');
          include_once($dirs['inc'] . 'notificaciones.php');
          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'admon':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $extras = "        
              $(function (){
                  $('#fechainicio').datepicker();
              });
            ";
          $style = "
            input[type=text], #select_admon {
              width: 25%;
              display: inline-block;
            }
            ";
          switch ($_GET['OPT']) {
            case 'select':
              if (isset($_GET['export']) && $_GET['export'] == 'marcajes') {
                include_once($dirs['inc'] . 'export_marcajes.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'asistencias') {
                include_once($dirs['inc'] . 'export_asistencias.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'faltas') {
                include_once($dirs['inc'] . 'export_faltas.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'horarios') {
                include_once($dirs['inc'] . 'export_horarios.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'profesores') {
                include_once($dirs['inc'] . 'export_profesores.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'fichajes') {
                include_once($dirs['inc'] . 'export_fichajes.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'marcajes') {
                include_once($dirs['inc'] . 'list_marcajes.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'asistencias') {
                include_once($dirs['inc'] . 'list_asistencias.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'faltas') {
                include_once($dirs['inc'] . 'list_faltas.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'horarios') {
                include_once($dirs['inc'] . 'list_horarios.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'fichadi') {
                include_once($dirs['inc'] . 'list_fichaje_diario.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'fichafe') {
                include_once($dirs['inc'] . 'list_fichaje_fecha.php');
              } else {
                header('Location: index.php');
              }
              break;

            default:
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'menu_admon.php');
              include_once($dirs['public'] . 'js/admon_filtrado_fecha.js');
              include_once($dirs['public'] . 'js/admon.js');
              include_once($dirs['inc'] . 'errors.php');
              include_once($dirs['inc'] . 'footer.php');
              break;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'fichar-asist':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'fichar-asistencia.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'fichar-manual':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          $extras = "
            $(function (){
              $('#add-fecha').datepicker({minDate: -5, maxDate: 0});
            });
          ";
          $scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
          include_once($dirs['inc'] . 'top-nav.php');
          include_once($dirs['inc'] . 'fichar-manual.php');
          include_once($dirs['inc'] . 'errors.php');
          include_once($dirs['inc'] . 'footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'fichar-mysql-manual':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'fichar-mysql-manual.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'plantilla-horarios':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'export-plantilla-horarios.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'plantilla-profesores':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'export-plantilla-profesores.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'download_admin_guide':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'export-guide-admin.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'download_profesor_guide':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'export-guide-profesor.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;

    case 'clean_tmp':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'clean_tmp.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'msg_modal.php');
      }
      break;
  }
} else {
  if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['inc'] . 'login_valida.php');
  }
  if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      if ($_SESSION['Perfil'] === 'Admin') {
        $act_home = 'active';
        $scripts = '<link rel="stylesheet" href="css/profesores.css">';
        if (isset($_POST['boton']) && $class->validRegisterProf()) {
          header('Location: index.php?ACTION=profesores');
        }
        include_once($dirs['inc'] . 'top-nav.php');
        include_once($dirs['inc'] . 'profesores.php');
        include($dirs['inc'] . 'errors.php');
        include($dirs['inc'] . 'footer.php');
      } elseif ($_SESSION['Perfil'] === 'Profesor') {
        $act_qr = 'active';
        include_once($dirs['inc'] . 'top-nav.php');
        include_once($dirs['inc'] . 'generate_code.php');
        include($dirs['inc'] . 'errors.php');
        include($dirs['inc'] . 'footer.php');
      } else {
        die('<h1 style="color:red;">Error de proceso...</h1>');
      }
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
  } else {
    include_once($dirs['inc'] . 'login_form.php');
  }
}
