<?php

// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

date_default_timezone_set('Europe/Madrid');
// Requerimos fichero de configuración esencial de directorios y constantes
require_once("./config.php");

// Requerimos seteado de variables para que no estén vacías y salte warning
require_once("./initial_vars.php");

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
            include_once($dirs['inc'] . 'Interfaces/header.php');
            include_once($dirs['inc'] . 'Interfaces/top-nav.php');
            include_once($dirs['inc'] . 'Profesores/profesores.php');
            include($dirs['inc'] . 'Interfaces/errors.php');
            include($dirs['inc'] . 'Interfaces/footer.php');
          } elseif ($_SESSION['Perfil'] === 'Profesor') {
            $act_qr = 'active';
            include_once($dirs['inc'] . 'Interfaces/header.php');
            include_once($dirs['inc'] . 'Interfaces/top-nav.php');
            include_once($dirs['inc'] . 'Qr/generate_code.php');
            include($dirs['inc'] . 'Interfaces/errors.php');
            include($dirs['inc'] . 'Interfaces/footer.php');
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
      include_once($dirs['inc'] . 'Login/admin-login.php');
      break;

    case 'logout':
      include_once($dirs['inc'] . 'Login/logout.php');
      break;

    case 'cambio_pass':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          $scripts = '<link rel="stylesheet" href="css/login-style.css">';
          include_once($dirs['inc'] . 'Valida/valida_new_pass.php');
          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');
          include_once($dirs['inc'] . 'Login/new_pass.php');
          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'primer_cambio':
      $scripts = '<link rel="stylesheet" href="css/login-style.css">';
      include_once($dirs['inc'] . 'Valida/valida_primer_cambio.php');
      include_once($dirs['inc'] . 'Interfaces/header.php');
      include_once($dirs['inc'] . 'Interfaces/top-nav.php');
      include_once($dirs['inc'] . 'Login/primer_cambio.php');
      include_once($dirs['inc'] . 'Interfaces/errors.php');
      include_once($dirs['inc'] . 'Interfaces/footer.php');
      break;

    case 'lectivos':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if ($response = $class->query("SELECT COUNT(*) as num FROM $class->marcajes")) {
            $act_cal_escolar = 'active';
            $marcajes = $response->fetch_assoc();
            if ($marcajes['num'] > 0) {
              $scripts = '<link rel="stylesheet" href="css/form.css">';
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              echo '<div class="container" style="margin-top: 50px;">';
              echo "<div class='row'>";
              echo "<div class='col-xs-12'>";
              include_once($dirs['inc'] . 'Horarios/calendario.php');
              echo "</div>";
              echo "</div>";
              echo "</div>";
              include_once($dirs['inc'] . 'Interfaces/errors.php');
              include_once($dirs['inc'] . 'Interfaces/footer.php');
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
              include_once($dirs['inc'] . 'Valida/valida-lectivos.php');
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Horarios/lectivos.php');
              include_once($dirs['public'] . 'js/lectivos.js');
              include_once($dirs['inc'] . 'Interfaces/errors.php');
              include_once($dirs['inc'] . 'Interfaces/footer.php');
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
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'qrcoder':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_qr = 'active';
          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');
          include_once($dirs['inc'] . 'Qr/generate_code.php');
          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'registrarse':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          if (isset($_POST['Nombre']) || isset($_POST['Iniciales']) || isset($_POST['pass1']) || isset($_POST['pass2'])) {
            include_once($dirs['inc'] . 'Valida/register_valida.php');
          } else {
            include_once($dirs['inc'] . 'Form/register_form.php');
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'horarios':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          $act_horario = 'active';

          switch ($_GET['OPT']) {
            case 'edit-horario':
                include_once($dirs['inc'] . 'Editar/edit-horario.php');
              break;
            case 'edit-t_horario':
              include_once($dirs['inc'] . 'Editar/edit-t_horario.php');
            break;

            case 'gest-horario':
              $extras = "
                  $(function (){
                      $('#fecha-programar-horario').datepicker({minDate: 1});
                  });
                ";
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
                #programar-horario {
                  text-align: center;
                }
                #program-date {
                  text-align: center;
                  color: grey;
                  margin-bottom: 25px;                  
                }
                #update-btn, #apply-program {
                  position: fixed;
                  top: 75px;
                  right: 25px;
                  background-color: #5cb85ccf;
                }
                #update-btn:hover, #apply-program:hover {
                  background-color: #449d44;
                }
                #cancel-btn, #cancel-program {
                  position: fixed;
                  top: 75px;
                  left: 25px;
                  background-color: #d9534fc4;
                }
                #cancel-btn:hover, #cancel-program:hover {
                  background-color: #d9534f;
                }
              ";
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              if (isset($_GET['programDate']) && $class->validFormDate($_GET['programDate'])) {
                include_once($dirs['inc'] . 'Horarios/gest-horario-programado.php');
              } else {
                include_once($dirs['inc'] . 'Horarios/gest-horario.php');
              }
              break;

            case 'crear':
              if ($_SESSION['Perfil'] == 'Admin') {
                $scripts = '<link rel="stylesheet" href="css/horarios-crear.css">';
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Horarios/crear-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Editar/cursos.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
            break;

            case 'edit-cursos':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Editar/edit-cursos.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Editar/aulas.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
            break;

            case 'edit-aulas':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Editar/edit-aulas.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Importar/import-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'preview':
              if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['inc'] . 'Importar/preview-import-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'import-csv':
              if ($_SESSION['Perfil'] == 'Admin') {
                require_once($dirs['inc'] . 'Importar/import-mysql-horario-ajax.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'edit-horario-profesor':
              if ($_SESSION['Perfil'] == 'Admin') {
                $scripts = '<link rel="stylesheet" href="css/horarios-edit.css">';
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Editar/edit-horario-profesor.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'edit-t':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Editar/edit-t-horario.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'update':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Interfaces/actualiza.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'registros':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Horarios/muestra-registros-horarios.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'guardias':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Horarios/horario-guardias.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'edit-guardias':
              if ($_SESSION['Perfil'] == 'Admin') {
                if (isset($_GET['SUBOPT'])) {
                    include_once($dirs['inc'] . 'Horarios/update-guardias.php');
                } else {
                  $scripts = '<link rel="stylesheet" href="css/horarios-edit-guardias.css">';
                  include_once($dirs['inc'] . 'Interfaces/header.php');
                  include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                  include_once($dirs['inc'] . 'Editar/edit-guardias.php');
                }
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'profesor':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Horarios/horario-profesor.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'remove':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Horarios/remove-horario-profesor.php');
                if (isset($ERR_MSG) && $ERR_MSG != '') {
                  header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
                } else {
                  header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
                }
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'delete-all':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Horarios/delete_all_horarios.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

              case 'delete-all-t':
                if ($_SESSION['Perfil'] == 'Admin') {
                  include_once($dirs['inc'] . 'Horarios/delete_all_t_horarios.php');
                } else {
                  $MSG = "Acceso denegado.";
                  header("Refresh:2; url=index.php");
                  include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
                }
                break;

            default:
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Horarios/horarios.php');
              break;
          }

          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');

          switch ($_GET['OPT'] ?? '') {
            case 'all':
              if ($_SESSION['Perfil'] === 'Admin') {
                include_once($dirs['inc'] . 'Fichaje/contenido-asistencias-all.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            case 'sesion':
              $_GET['ID'] = $_SESSION['ID'];
              include_once($dirs['inc'] . 'Fichaje/contenido-asistencias.php');
              break;

            default:
              include_once($dirs['inc'] . 'Fichaje/contenido-asistencias.php');
              break;
          }

          include_once('js/filtro_asistencias.js');
          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }

      break;

    case 'profesores':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_profesores = 'active';
          if (!isset($_GET['OPT'])) {
            $_GET['OPT'] = '';
          }
          switch ($_GET['OPT']) {
            case 'import-form':
              $style = "
                  input[type=file] {
                    display: inline-block;
                    padding: 6px 12px 6px 0;
                  }
                ";
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Importar/import-profesorado.php');
              break;

            case 'preview':
              include_once($dirs['inc'] . 'Importar/preview-import-profesores.php');
              break;

            case 'import-csv':
              //require_once($dirs['inc'] . 'import-mysql-profesorado.php');
              require_once($dirs['inc'] . 'Importar/import-mysql-profesorado-ajax.php');
              break;

            case 'registros':
              include_once($dirs['inc'] . 'Profesores/muestra-registros-profesores.php');
              break;

            case 'edit':
              $scripts = '<link rel="stylesheet" href="css/login-style.css">';
              $scripts .= '<link rel="stylesheet" href="css/profesores-edit.css">';
              include_once($dirs['inc'] . 'Valida/valida_edit_profesor.php');
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Profesores/editar_profesor.php');
              break;

            case 'sustituir':
              $scripts = '<link rel="stylesheet" href="css/login-style.css">';
              /*$scripts .= '<link rel="stylesheet" href="css/profesores-edit.css">';*/
              $scripts = '<link rel="stylesheet" href="css/profesores-sustituir.css">';
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Form/form_sustituto.php');
              break;

            case 'add-profesor':
              if (isset($_POST['add-profesor']) && $_POST['add-profesor'] === 'add') {
                if ($class->validRegisterProf()) {
                  $MSG = "Profesor: $_POST[Nombre] con iniciales: $_POST[Iniciales] añadido correctamente";
                  header('Refresh: 2; index.php?ACTION=profesores');
                  include_once($dirs['inc'] . 'Interfaces/header.php');
                  include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                } else {
                  include_once($dirs['inc'] . 'Interfaces/header.php');
                  include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                  include_once($dirs['inc'] . 'Form/form-add-profesor.php');
                }
              } else {
                include_once($dirs['inc'] . 'Interfaces/header.php');
                include_once($dirs['inc'] . 'Interfaces/top-nav.php');
                include_once($dirs['inc'] . 'Form/form-add-profesor.php');
              }
              break;

            case 'add-sustituto':
              include_once($dirs['inc'] . 'Profesores/agregar-sustituto.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'remove-sustituto':
              include_once($dirs['inc'] . 'Profesores/retirar-sustituto.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'des-act':
              include_once($dirs['inc'] . 'Profesor/des-act-profesor.php');
              if (isset($ERR_MSG) && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'reset-pass':
              include_once($dirs['inc'] . 'Login/reset_pass.php');
              if (isset($ERR_MSG)  && $ERR_MSG != '') {
                header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
              } else {
                header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
              }
              break;

            case 'delete-all':
              if ($_SESSION['Perfil'] == 'Admin') {
                include_once($dirs['inc'] . 'Profesores/delete_all_profesores.php');
              } else {
                $MSG = "Acceso denegado.";
                header("Refresh:2; url=index.php");
                include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
              }
              break;

            default:
              $scripts = '<link rel="stylesheet" href="css/profesores.css">';
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Profesores/profesores.php');
              break;
          }

          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'marcajes':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          switch ($_GET['OPT']) {
            case 'update':
              include_once($dirs['inc'] . 'Horarios/update-marcajes.php');
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
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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

          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');
          include($dirs['inc'] . 'Interfaces/home.php');
          include($dirs['inc'] . 'Interfaces/errors.php');
          include($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
        include($dirs['inc'] . 'Qr/qr-webcam-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-webcam-admin-login.js');
      } else {
        echo '<div class="container-fluid" style="margin-top:50px">';
        echo "<div class='row'>";
        echo "<div id='qreader' class='col-xs-12' style='margin-top: 20vh;'>";
        include($dirs['inc'] . 'Qr/qr-reader-admin-login.php');
        echo "</div>";
        echo "</div>";
        echo "</div>";
        include_once($dirs['public'] . 'js/qr-reader-admin-login.js');
      }
      include($dirs['inc'] . 'Interfaces/errors.php');
      include($dirs['inc'] . 'Interfaces/footer.php');
      break;

    case 'notificaciones':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          $act_usuario = 'active';
          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');
          include_once($dirs['inc'] . 'Interfaces/notificaciones.php');
          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
          if (!isset($_GET['OPT'])) {
            $_GET['OPT'] = '';
          }
          switch ($_GET['OPT']) {
            case 'select':
              if (isset($_GET['export']) && $_GET['export'] == 'marcajes') {
                include_once($dirs['inc'] . 'Exportar/export_marcajes.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'asistencias') {
                include_once($dirs['inc'] . 'Exportar/export_asistencias.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'faltas') {
                include_once($dirs['inc'] . 'Exportar/export_faltas.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'horarios') {
                include_once($dirs['inc'] . 'Exportar/export_horarios.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'profesores') {
                include_once($dirs['inc'] . 'Exportar/export_profesores.php');
              } elseif (isset($_GET['export']) && $_GET['export'] == 'fichajes') {
                include_once($dirs['inc'] . 'Exportar/export_fichajes.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'marcajes') {
                include_once($dirs['inc'] . 'Listar/list_marcajes.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'asistencias') {
                include_once($dirs['inc'] . 'Listar/list_asistencias.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'faltas') {
                include_once($dirs['inc'] . 'Listar/list_faltas.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'horarios') {
                include_once($dirs['inc'] . 'Listar/list_horarios.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'fichadi') {
                include_once($dirs['inc'] . 'Listar/list_fichaje_diario.php');
              } elseif (isset($_GET['select']) && $_GET['select'] == 'fichafe') {
                include_once($dirs['inc'] . 'Listar/list_fichaje_fecha.php');
              } else {
                header('Location: index.php');
              }
              break;

            default:
              include_once($dirs['inc'] . 'Interfaces/header.php');
              include_once($dirs['inc'] . 'Interfaces/top-nav.php');
              include_once($dirs['inc'] . 'Interfaces/menu_admon.php');
              include_once($dirs['public'] . 'js/admon_filtrado_fecha.js');
              include_once($dirs['public'] . 'js/admon.js');
              include_once($dirs['inc'] . 'Interfaces/errors.php');
              include_once($dirs['inc'] . 'Interfaces/footer.php');
              break;
          }
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'fichar-asist':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'Fichaje/fichar-asistencia.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
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
          include_once($dirs['inc'] . 'Interfaces/header.php');
          include_once($dirs['inc'] . 'Interfaces/top-nav.php');
          include_once($dirs['inc'] . 'Fichaje/fichar-manual.php');
          include_once($dirs['inc'] . 'Interfaces/errors.php');
          include_once($dirs['inc'] . 'Interfaces/footer.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'fichar-mysql-manual':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'Fichaje/fichar-mysql-manual.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'plantilla-horarios':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'Exportar/export-plantilla-horarios.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

      case 'backup-centro':
        if ($class->isLogged($Titulo) && $_SESSION['Perfil'] == 'Admin') {
          if ($class->compruebaCambioPass()) {
            include_once($dirs['inc'] . 'Exportar/backup-centro.php');
          } else {
            header('Location: index.php?ACTION=primer_cambio');
          }
        } else {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
        }
      break;

    case 'plantilla-profesores':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'Exportar/export-plantilla-profesores.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'download_admin_guide':
      if ($class->isLogged($Titulo) && $_SESSION['Perfil'] === 'Admin') {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'Exportar/export-guide-admin.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'download_profesor_guide':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          require_once($dirs['inc'] . 'Exportar/export-guide-profesor.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;

    case 'clean_tmp':
      if ($class->isLogged($Titulo)) {
        if ($class->compruebaCambioPass()) {
          include_once($dirs['inc'] . 'Helper/clean_tmp.php');
        } else {
          header('Location: index.php?ACTION=primer_cambio');
        }
      } else {
        $MSG = "Debes iniciar sesión para realizar esta acción.";
        header("Refresh:2; url=index.php");
        include_once($dirs['inc'] . 'Interfaces/msg_modal.php');
      }
      break;
  }
} else {
  if (isset($_POST['Iniciales']) || isset($_POST['pass'])) {
    require_once($dirs['inc'] . 'Login/login_valida.php');
  }
  if ($class->isLogged($Titulo)) {
    if ($class->compruebaCambioPass()) {
      if ($_SESSION['Perfil'] === 'Admin') {
        $act_home = 'active';
        $scripts = '<link rel="stylesheet" href="css/profesores.css">';
        if (isset($_POST['boton']) && $class->validRegisterProf()) {
          header('Location: index.php?ACTION=profesores');
        }
        include_once($dirs['inc'] . 'Interfaces/header.php');
        include_once($dirs['inc'] . 'Interfaces/top-nav.php');
        include_once($dirs['inc'] . 'Profesores/profesores.php');
        include($dirs['inc'] . 'Interfaces/errors.php');
        include($dirs['inc'] . 'Interfaces/footer.php');
      } elseif ($_SESSION['Perfil'] === 'Profesor') {
        $act_qr = 'active';
        include_once($dirs['inc'] . 'Interfaces/header.php');
        include_once($dirs['inc'] . 'Interfaces/top-nav.php');
        include_once($dirs['inc'] . 'Qr/generate_code.php');
        include($dirs['inc'] . 'Interfaces/errors.php');
        include($dirs['inc'] . 'Interfaces/footer.php');
      } else {
        die('<h1 style="color:red;">Error de proceso...</h1>');
      }
    } else {
      header('Location: index.php?ACTION=primer_cambio');
    }
  } else {
    include_once($dirs['inc'] . 'Login/login_form.php');
  }
}
