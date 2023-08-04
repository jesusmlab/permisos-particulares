# Aplicación de gestión de permisos particulares para docentes

Esta aplicación pretende automatizar las peticiones de permisos particulares de los docentes de un centro ayudando a la Secretaría del Centro y al Director.

Se parte de que los profesores se podrán conectar a la misma para hacer la petición. Una vez autenticados los podrán rellenar la solicitud.

Dicha solicitud tendrá en cuenta las "reglas" pertinentes que dicta la normativa para estos asuntos (Nº de dias, antelación,topes, etc.).

Los profesores siempre podrán ver cuantas solicitudes aprobadas hay en cada dia de cada mes en la opcion "ver como está el patio". Con ello la elección del dia que se quiere pedir estará condicionada por la cantidad de profesores que ya han pedido ese mismo día.

En la solicitud se imprimirá una rubrica de nuestra firma que podemos hacer con el ratón o en pantalla tactil (smartphone) con el dedo.

La solicitud que se hagan serán visualizadas por los profesores y dependiendo de su estado estos podrán borrarlas (si todavia no han sido registradas por la secretaría del centro) o anularlas (si ya han sido registradas, pero no disfrutadas). Si ya se han disfrutado no se podrán tocar.

La Secretaría será avisada por email de que tiene solicitudes por atender. La mecánica de los usuarios de secretaría será la de imprimir la solicitud en papel, registrarla en el registro correspondiente y archivarla la copia impresa.

Igualmente el Director del centro será avisado por email de que tiene solicitudes que resolver (las que se hayan registrado en Secretaría), por lo que el director podrá , ayudado por herramientas de visualización de los permisos pendientes, conceder y denegar solicitudes, dependiendo de las reglas que se apliquen (orden de llegada, nº de petición, topes, etc.).

Además el Director tendrá la prerrogativa de Crear, Borrar y Modificar cualquier solicitud.

Se enviará un correo al profesor en cuestión, cuando se resuelva su solicitus. Si la resolución es positiva, en el correo se adjuntará una nota de icalendar, con la que podrá insertar dicho dia en el calendario que use (Google Calendar, Microsoft Outlook, etc).

# Conexion aplicación guardias

Está aplicación puede funcionar conectada a la aplicacion de guardias, de forma que cuando se concede un permiso se genera automáticamente un apunte de **falta** para dicho profesor para el dia elegido de permiso.

Está conexión se habilita y configura en el fichero **config.php** visto mas adelante.

# Tecnologia utilizada en esta aplicación

- Servidor LAMP o WAMP (Linux/Windows Apache2 Mysql/MariaDB y PHP)
- Framework MVC casero
- DataTables
- Bootstrap 5
- Jquery 3.6
- HTML2PDF
- FullCalendar
- icalendar
- moments.js
- PHP Mailer
- JQuery signaturepad

# Instalación BBDD

La instalación es la típica de una aplicación LAMP, basta con copiar la aplicación en una carpeta de nuestro servidor WEB, crear la BBDD con el script **permisos.sql**.

Hay que configurar el fichero **system/core/config.php** con las credenciales de acceso a la BBDD.

Esta Base de datos será la de **permisos.sql** si la aplicación no está conectada a las guardias . En caso contrario esta aplicación deberá tener sus tablas creadas en la misma BBDD que las guardias y se creará con **(permisos-guardias.sql)**

En la BBDD inicial hay creado un usuario administrador:

usuario: **admin**
password: **peradm123\***

# Configuración

Hay un fichero en _system/core_ llamado **config.php** que contiene toda una serie de constantes con las siguientes configuraciones:

- Configuraciones de MVC.(carpetas para localización de los diversos componentes)
- Conexion con la Base de datos (Host,BBDD,usuario y password)
- Autenticación de Usuarios (propia de la aplicación o LDAP)
- Envio de emails (servidor SMTP, usuario y password)
- Conexion con aplicación de Guardias (Si o NO)
- Otras configuraciones (Nombre del Centro, etc)

Hay un fichero llamado **solicitusPermiso_pdf.php** en _app/vistas_ que contiene HTML que sirve de plantilla para generar las solicitudes en PDF. Modificaremos los textos necesarios e imágenes , adaptandolos a nuestro centro.

Existe un fichero dentro de _\app\assets_ llamado **correo.html** que sirve de máscara para el envio de correos. Cambiar el titulo y el link a la aplicación.

# Notas adiccionales

La carpeta \_app/assets/documentos\* contendrá todos los documentos generados por las solicitudes (PDF y ical), por lo que el usuario de nuestro servidor WEB deberá poder escribir en él.

En la carpeta _app/assets/img_ hay logos genericos que deben ser sustituidos por los del centro donde se use.

# Configuraciónes de PHP (php.ini)

Hay que activar las extensiones **gd** , **intl** y **pdo_mysql**.
Hay que poner short_open_tag=On.
