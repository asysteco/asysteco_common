<?php
// Iniciamos las variables de sesión con @ para que no nos devuelva warnings si la sesión ya estaba iniciada
@session_start();

// Requerimos el fichero de configuración de directorios
// Requerimos los ficheros de las clases que hemos creado
$subrootsplit = preg_split('/\//', $_SERVER['REQUEST_URI']);
$subroot = '/' . $subrootsplit[1];
preg_match('/^\/[A-Z]+$/i', $subroot) ? $subroot = $subroot : $subroot = '' ;
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . $subroot . '/inc/dir_config.php');
require_once($basedir . $subdir . '/config_instituto.php');
require_once($dirs['class'] . 'Netasys.php');
// iniciamos las clases y las guardamos en variables
$class = new Netasys;
$class->bdConex($insti_host, $insti_user, $insti_pass, $insti_db);
// Comprobamos si existen horarios para actualizar

$class->tempToValid();

// Comprobamos si está seteada la variable ACTION en la URL (Método GET)
// Si no es así, procedemos a validar el login, si este es correcto cargamos el fichero home.php
// En su defecto cargaremos el formulario de login

if(isset($_GET['ACTION']))
{
    switch($_GET['ACTION'])
    {
      default:
        if(isset($_POST['Iniciales']) || isset($_POST['pass']))
        {
          require_once($dirs['inc'] . 'login_valida.php');
        }
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_home = 'active';
            include($dirs['inc'] . 'home.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
            include_once($dirs['inc'] . 'login_form.php');
        }
      break;
    
      case 'logout':
        include_once($dirs['inc'] . 'logout.php');
      break;
    
      case 'pruebas':
        include_once($dirs['inc'] . 'top-nav.php');
        include_once($dirs['inc'] . 'pruebas.php');
        include_once($dirs['inc'] . 'errors.php');
        include_once($dirs['inc'] . 'footer.php');
      break;
  
      case 'cambio_pass':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_usuario = 'active';
            $extras = '<link rel="stylesheet" href="css/login-style.css">';
            include_once($dirs['inc'] . 'valida_new_pass.php');
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'new_pass.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
  
      case 'primer_cambio':
        $extras = '<link rel="stylesheet" href="css/login-style.css">';
        include_once($dirs['inc'] . 'valida_primer_cambio.php');
        include_once($dirs['inc'] . 'top-nav.php');
        include_once($dirs['inc'] . 'primer_cambio.php');
        include_once($dirs['inc'] . 'errors.php');
        include_once($dirs['inc'] . 'footer.php');
      break;
    
      case 'lectivos':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            if($response = $class->query("SELECT COUNT(*) as num FROM $class->marcajes"))
            {
              $act_cal_escolar = 'active';
              $marcajes = $response->fetch_assoc();
              if($marcajes['num'] > 0)
              {
                $extras = '<link rel="stylesheet" href="css/form.css">';
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
              }
              else
              {
                $extras = '<link rel="stylesheet" href="css/form.css">';
                $extras .= "<script>
                $.datepicker.regional['es'] = {
                closeText: 'Cerrar',
                prevText: '< Ant',
                nextText: 'Sig >',
                currentText: 'Hoy',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
                weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
                };
                $.datepicker.setDefaults($.datepicker.regional['es']);
            
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
              </script>";
                include_once($dirs['inc'] . 'valida-lectivos.php');
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'lectivos.php');
                include_once($dirs['public'] . 'js/lectivos.js');
                include_once($dirs['inc'] . 'errors.php');
                include_once($dirs['inc'] . 'footer.php');
              }
            }
            else
            {
              $ERR_MSG = $class->ERR_NETASYS;
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'qrcoder':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'generate_code.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'registrarse':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            if(isset($_POST['Nombre']) || isset($_POST['Iniciales']) || isset($_POST['pass1']) || isset($_POST['pass2']))
            {
              include_once($dirs['inc'] . 'register_valida.php');
            }
            else
            {
              include_once($dirs['inc'] . 'register_form.php');
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'horarios':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_horario = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-horarios.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'crear-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_horario = 'active';
            $style = "
            .entrada {
              display: none;
            }
            .btn-react-del {
              transform: scale(1.4);
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-del:hover {
              color: #b30c0c;
              transform: scale(1.6);
            }

            .btn-react-add {
              transform: scale(1.4);
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-add:hover {
              color: green;
              transform: scale(1.6);
            }

            .btn-react-add-more {
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-add-more:hover {
              color: green;
              transform: scale(1.2);
            }

            .btn-react-del-group {
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-del-group:hover {
              color: #b30c0c;
              transform: scale(1.2);
            }

            #select_tipo {
              background-color: #f6f6f6;
              border: none;
              color: #0d0d0d;
              padding: 15px 32px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
              font-size: 16px;
              margin: 5px;
              width: 85%;
              border: 2px solid #f6f6f6;
              -webkit-transition: all 0.5s ease-in-out;
              -moz-transition: all 0.5s ease-in-out;
              -ms-transition: all 0.5s ease-in-out;
              -o-transition: all 0.5s ease-in-out;
              transition: all 0.5s ease-in-out;
              -webkit-border-radius: 5px 5px 5px 5px;
              border-radius: 5px 5px 5px 5px;
            }
            ";
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'crear-horario.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
        
      case 'modificar-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_horario = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'modificar-horario.php');
            include_once($dirs['inc'] . 'contenido-horarios.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
        
      case 'edit-horario-profesor':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_horario = 'active';
            $style = "
            .entrada {
              display: none;
            }
            .btn-react-del {
              transform: scale(1.4);
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-del:hover {
              color: #b30c0c;
              transform: scale(1.6);
            }

            .btn-react-add {
              transform: scale(1.4);
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-add:hover {
              color: green;
              transform: scale(1.6);
            }

            .btn-react-add-more {
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-add-more:hover {
              color: green;
              transform: scale(1.2);
            }

            .btn-react-del-group {
              transition-duration: 0.1s;
              cursor: pointer;
            }
            .btn-react-del-group:hover {
              color: #b30c0c;
              transform: scale(1.2);
            }
            ";
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'edit-horario-profesor.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
        
      case 'edit-t-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'edit-t-horario.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
        
      case 'edit-crear-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'edit-crear-horario.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
        
      case 'nuevo-registro-horario-profesor':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_profesores = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'nuevo-registro-horario-profesor.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'update-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'actualiza.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'update-t-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'actualiza.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'asistencias':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_asistencia = 'active'; $extras .= "<script>
            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        
            $(function (){
                $('#busca_asiste').datepicker();
            });
            </script>";
            $style = "
            input[type=text], input[type=password] {
            background-color: #f6f6f6;
            border: none;
            color: #0d0d0d;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            width: 85%;
            border: 2px solid #f6f6f6;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
            }
            
            input[type=text]:focus {
            background-color: #fff;
            border-bottom: 2px solid #5fbae9;
            }
            
            input[type=text]:placeholder {
            color: #cccccc;
            }";
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-fichajes.php');
            include_once('js/filtro_asistencias.js');
            include_once('js/update_marcajes.js');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
        
      break;
    
      case 'import-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_horario = 'active';
            $extras = "<script>
            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        
            $(function (){
                $('#fecha_incorpora').datepicker({minDate: +1});
            });
            </script>";
            $style = "
            input[type=file] {
              display: inline-block;
              padding: 6px 12px 6px 0;
            }";
            if (isset($_POST["import"]))
            {
                require_once($dirs['inc'] . 'import-mysql-horario.php');
                require_once($dirs['inc'] . 'actualiza_horas.php');
            }
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-import-horario.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }

      break;
    
      case 'muestra-registros-horarios':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'muestra-registros-horarios.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'muestra-registros-profesores':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'muestra-registros-profesores.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'Agregar-registro-horario':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            header("Refresh:0; url=index.php?ACTION=profesores");
            include_once($dirs['inc'] . 'agregar-registro-horario.php');
            include_once($dirs['inc'] . 'msg_modal.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'import-profesorado':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_profesores = 'active';
            $style = "
            input[type=file] {
              display: inline-block;
              padding: 6px 12px 6px 0;
            }";
            if (isset($_POST["import"]))
            {
				require_once($dirs['inc'] . 'import-mysql-profesorado.php');
            }
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-import-profesorado.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'profesores':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_profesores = 'active';
			$extras = "
				<script>
				$.datepicker.regional['es'] = {
					closeText: 'Cerrar',
					prevText: '< Ant',
					nextText: 'Sig >',
					currentText: 'Hoy',
					monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
					monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
					dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
					dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
					dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
					weekHeader: 'Sm',
					dateFormat: 'dd/mm/yy',
					firstDay: 1,
					isRTL: false,
					showMonthAfterYear: false,
					yearSuffix: ''
				};
				$.datepicker.setDefaults($.datepicker.regional['es']);
			</script>";
            $style = "
            .reset_icon {
              transition-duration: 0.4s;
            }
            .reset_icon:hover {
              transform: rotate(360deg) scale(1.4);
            }

            .edit_icon, .list_icon {
              transition-duration: 0.2s;
            }
            .edit_icon:hover, .list_icon:hover {
              transform: scale(1.4);
            }

            .remove_icon {
              transition-duration: 0.2s;
            }
            .remove_icon:hover {
              transform: scale(1.4);
              color: red;
            }

            .add_icon {
              transition-duration: 0.2s;
            }
            .add_icon:hover {
              transform: scale(1.4);
              color: green;
            }

            .row_show:hover {
              cursor: pointer;
            }
            #tabla_profesores td, #tabla_profesores th{
              text-align: center;
              vertical-align: middle;
            }
            ";
            if(isset($_GET['profesor']) && $_GET['profesor'] != '')
            {
              include_once($dirs['inc'] . 'contenido-horario-profesor.php');
            }
            else
            {
              if(isset($_POST['boton']))
              {
                if($class->validRegisterProf())
                {
                  header('location: index.php?ACTION=profesores');
                }
                else
                {
                  include_once($dirs['inc'] . 'top-nav.php');
                  include_once($dirs['inc'] . 'contenido-profesores.php');
                  include_once($dirs['inc'] . 'errors.php');
                  include_once($dirs['inc'] . 'footer.php');
                }
              }
              else
              {
                include_once($dirs['inc'] . 'top-nav.php');
                include_once($dirs['inc'] . 'contenido-profesores.php');
                include_once($dirs['inc'] . 'errors.php');
                include_once($dirs['inc'] . 'footer.php');
              }
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }   
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'select_celda_horario';
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'select-celdas-horario.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
  
      case 'editar_profesor':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'valida_edit_profesor.php');
            $extras = '<link rel="stylesheet" href="css/login-style.css">';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'editar_profesor.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          } 
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'formulario-sustituto':
        {
          if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
          {
            if($class->compruebaCambioPass())
            {
              $style = '
                #select_sustituto {
                  background-color: #f6f6f6;
                  border: none;
                  color: #0d0d0d;
                  padding: 15px 32px;
                  text-align: center;
                  text-decoration: none;
                  display: inline-block;
                  font-size: 16px;
                  margin: 5px;
                  width: 50%;
                  border: 2px solid #f6f6f6;
                  -webkit-transition: all 0.5s ease-in-out;
                  -moz-transition: all 0.5s ease-in-out;
                  -ms-transition: all 0.5s ease-in-out;
                  -o-transition: all 0.5s ease-in-out;
                  transition: all 0.5s ease-in-out;
                  -webkit-border-radius: 5px 5px 5px 5px;
                  border-radius: 5px 5px 5px 5px;
                }
              ';
              include_once($dirs['inc'] . 'top-nav.php');
              include_once($dirs['inc'] . 'formulario-sustituto.php');
              include_once($dirs['inc'] . 'errors.php');
              include_once($dirs['inc'] . 'footer.php');
            }
            else
            {
              header('Location: index.php?ACTION=primer_cambio');
            } 
          }
          else
          {
            $MSG = "Debes iniciar sesión para realizar esta acción.";
            header("Refresh:2; url=index.php");
            include_once($dirs['inc'] . 'msg_modal.php');
          }
        }
      break;

      case 'Agregar-sustituto':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'agregar-sustituto-profesor.php');
            if(isset($ERR_MSG)  && $ERR_MSG != '')
            {
              header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            }
            else
            {
              header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          } 
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'retirar-sustitucion':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'retirar-sustituto-profesor.php');
            header('location: index.php?ACTION=profesores');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          } 
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'desactivar-activar-profesor':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'update-activo-profesor.php');
            if(isset($ERR_MSG) && $ERR_MSG != '')
            {
              header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            }
            else
            {
              header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          } 
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
      
      case 'reset_pass':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'reset_pass.php');
            if(isset($ERR_MSG)  && $ERR_MSG != '')
            {
              header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            }
            else
            {
              header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
          }
          else
          {
            header('Location: index.php');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'delete-horario-profesor':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'delete-horario-profesor.php');
            if(isset($ERR_MSG) && $ERR_MSG != '')
            {
              header("Location: index.php?ACTION=profesores&ERR_MSG=" . $ERR_MSG);
            }
            else
            {
              header("Location: index.php?ACTION=profesores&MSG=" . $MSG);
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          } 
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'faltas_profesor':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            $extras .= "<script>
            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        
            $(function (){
                $('#busca_asiste').datepicker();
            });
          </script>";
            $style = "
            input[type=text], input[type=password] {
            background-color: #f6f6f6;
            border: none;
            color: #0d0d0d;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 5px;
            width: 85%;
            border: 2px solid #f6f6f6;
            -webkit-transition: all 0.5s ease-in-out;
            -moz-transition: all 0.5s ease-in-out;
            -ms-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            -webkit-border-radius: 5px 5px 5px 5px;
            border-radius: 5px 5px 5px 5px;
            }
            
            input[type=text]:focus {
            background-color: #fff;
            border-bottom: 2px solid #5fbae9;
            }
            
            input[type=text]:placeholder {
            color: #cccccc;
            }
            #full-table td, #full-table th {
              text-align: center;
              vertical-align: middle;
            }
            ";
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-asistencias.php');
            include_once('js/filtro_asistencias.js');
            include_once('js/update_marcajes.js');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'create_marcajes':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin' || $_SESSION['Perfil'] == 'Profesor')
        {
          if($class->compruebaCambioPass())
          {
            header("Refresh:2; url=$_SERVER[HTTP_REFERER]");
            include_once($dirs['inc'] . 'marcajes.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'update_marcajes':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin' || $_SESSION['Perfil'] == 'Profesor')
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'update-marcajes.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'guardias':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_guardias = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'contenido-guardias.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'form_mensajes':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_usuario = 'active';
            $extras = '<link rel="stylesheet" href="css/mensajes.css">';
            $extras .= '<link rel="stylesheet" href="css/message.css">';
            $extras .= '<script>
            $( function() {
              $( "#tabs" ).tabs();
            } );
            </script>';
            $style = '
              html {
                background-color: white;
              }
              body {
                height: 100%;
              }

              #select_mensaje {
                background-color: #f6f6f6;
                border: none;
                color: #0d0d0d;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 5px;
                width: 85%;
                border: 2px solid #f6f6f6;
                -webkit-transition: all 0.5s ease-in-out;
                -moz-transition: all 0.5s ease-in-out;
                -ms-transition: all 0.5s ease-in-out;
                -o-transition: all 0.5s ease-in-out;
                transition: all 0.5s ease-in-out;
                -webkit-border-radius: 5px 5px 5px 5px;
                border-radius: 5px 5px 5px 5px;
              }
            ';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'form_mensajes.php');
            include_once($dirs['inc'] . 'listar_mensajes.php');
            include_once($dirs['public'] . 'js/menu_mensaje.js');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'enviar_mensaje':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'enviar_mensaje.php');
          }
          else
          {
            header('Location: index.php?ACTION=form_mensajes');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'eliminar_mensaje':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'eliminar_mensaje.php');
          }
          else
          {
            header('Location: index.php?ACTION=listar_mensajes');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'notificaciones':
        if($class->isLogged() && $_SESSION['Perfil'] == 'Admin')
        {
          if($class->compruebaCambioPass())
          {
            $act_usuario = 'active';
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'notificaciones.php');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'Edificio':
        if(isset($_GET['Numero']))
        {
          if($class->isLogged())
          {
            if($class->compruebaCambioPass())
            {
              $act_home = 'active';
              $extras = '
              <style>
                canvas {box-shadow: 4px 6px 15px grey; padding: 2px; border-radius: 10px;}
                .respuesta span {display: block; box-shadow: 4px 6px 15px grey; padding: 50px; border-radius: 10px; margin-top: 30px;}
              </style>
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
              if($_SESSION['Perfil'] === 'Admin')
              {
                $style = '
                    .filtro_edificio {
                        margin-top: 50px;
                    }
                    #qreader {
                        text-align: center;
                    }
                ';
                include($dirs['inc'] . 'top-nav.php');
                include($dirs['inc'] . 'contenido-home.php');
                echo "<div class='row'>";
                    echo "<div id='qreader' class='col-xs-12 col-md-4' >";
                        echo "<h3>Fichaje</h3>";
                        include($dirs['inc'] . 'qr-reader.php');
                    echo "</div>";
                    echo "<div class='col-xs-12 col-md-8' >";
                        include($dirs['inc'] . 'filtro-edif-guardias.php');
                        include($dirs['inc'] . 'contenido-guardias.php');
                    echo "</div>";
                echo "</div>";
                include($dirs['inc'] . 'errors.php');
                include($dirs['inc'] . 'footer.php');
              }
              else
              {
                $style = '
                .filtro_edificio {
                    margin-top: 50px;
                }
                ';
                include($dirs['inc'] . 'top-nav.php');
                include($dirs['inc'] . 'contenido-home.php');
                include($dirs['inc'] . 'filtro-edif-guardias.php');
                include($dirs['inc'] . 'contenido-guardias.php');
                include($dirs['inc'] . 'errors.php');
                include($dirs['inc'] . 'footer.php');
              }
            }
            else
            {
              header('Location: index.php?ACTION=primer_cambio');
            }
          }
          else
          {
            $MSG = "Debes iniciar sesión para realizar esta acción.";
            header("Refresh:2; url=index.php");
            include_once($dirs['inc'] . 'msg_modal.php');
          }
        }
        else
        {
          header("Location: url=index.php");
        }
      break;

      case 'admon':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            $act_usuario = 'active';
            $extras .= "<script>
            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '< Ant',
            nextText: 'Sig >',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
            };
            $.datepicker.setDefaults($.datepicker.regional['es']);
        
            $(function (){
                $('#fichafeini').datepicker();
                $('#marcajefeini').datepicker();
            });
            </script>
            ";
            $style = "
            input[type=text], #select_admon_marcajes {
              width: 25%;
              display: inline-block;
            }
            ";
            include_once($dirs['inc'] . 'top-nav.php');
            include_once($dirs['inc'] . 'menu_admon.php');
            include_once($dirs['public'] . 'js/admon.js');
            include_once($dirs['public'] . 'js/temp_marcaje.js');
            include_once($dirs['public'] . 'js/temp_ficha.js');
            include_once($dirs['inc'] . 'errors.php');
            include_once($dirs['inc'] . 'footer.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;

      case 'admon_select':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            if(isset($_GET['export']) && $_GET['export'] == 'marcajes')
            {
              include_once($dirs['inc'] . 'export_marcajes.php');
            }
            if(isset($_GET['export']) && $_GET['export'] == 'asistencias')
            {
              include_once($dirs['inc'] . 'export_asistencias.php');
            }
            elseif(isset($_GET['export']) && $_GET['export'] == 'faltas')
            {
              include_once($dirs['inc'] . 'export_faltas.php');
            }
            elseif(isset($_GET['export']) && $_GET['export'] == 'horarios')
            {
              include_once($dirs['inc'] . 'export_horarios.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'marcajes')
            {
              include_once($dirs['inc'] . 'list_marcajes.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'asistencias')
            {
              include_once($dirs['inc'] . 'list_asistencias.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'faltas')
            {
              include_once($dirs['inc'] . 'list_faltas.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'horarios')
            {
              include_once($dirs['inc'] . 'list_horarios.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'fichadi')
            {
              include_once($dirs['inc'] . 'ficha_diario.php');
            }
            elseif(isset($_GET['select']) && $_GET['select'] == 'fichafe')
            {
              include_once($dirs['inc'] . 'ficha_fecha.php');
            }
            else
            {
              header('Location: index.php');
            }
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dsubdirirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'fichar-asist':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'fichar-asistencia.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    
      case 'clean_tmp':
        if($class->isLogged())
        {
          if($class->compruebaCambioPass())
          {
            include_once($dirs['inc'] . 'clean_tmp.php');
          }
          else
          {
            header('Location: index.php?ACTION=primer_cambio');
          }
        }
        else
        {
          $MSG = "Debes iniciar sesión para realizar esta acción.";
          header("Refresh:2; url=index.php");
          include_once($dirs['inc'] . 'msg_modal.php');
        }
      break;
    }
}
else
{
  if(isset($_POST['Iniciales']) || isset($_POST['pass']))
  {
    require_once($dirs['inc'] . 'login_valida.php');
  }
  if($class->isLogged())
  {
    if($class->compruebaCambioPass())
    {
      $act_home = 'active';
      include($dirs['inc'] . 'home.php');
    }
    else
    {
      header('Location: index.php?ACTION=primer_cambio');
    }
  }
  else
  {
      include_once($dirs['inc'] . 'login_form.php');
  }
}