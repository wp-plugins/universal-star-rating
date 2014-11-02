<?php

//Error Messages
$MESSAGES['ERROR']['NotEnoughParameters']['es'] = "[ERROR] Necesita inserir m&aacute;s de 1 nombre/rating. Utilize <code>[usr=3.5]</code> si necesita usar un unico nombre/rating.";
$MESSAGES['ERROR']['StarSizeNotNumeric']['es'] = "[ERROR] La dimensi&oacute;n inserida no corresponde a un valor numerico!";

//Info Messages
$MESSAGES['INFO']['SettingsUpdated']['es'] = "Opciones actualizadas";

//Configuration
$CONFIGURATION['DecimalMark']['es'] = ",";
$CONFIGURATION['AverageText']['es'] = "Promedio";

//Settings Global
$SETTINGS['GLOBAL']['Settings']['es'] = "Opciones";
$SETTINGS['GLOBAL']['SubmitButton']['es'] = "Aplicar las nuevas configuraciones";
$SETTINGS['GLOBAL']['ResetButton']['es'] = "Restablecer configuraci&oacute;n";

//Settings "Notes on usage"
$SETTINGS['NOU']['NotesOnUsage']['es'] = "Notas sobre el uso";
$SETTINGS['NOU']['ShortCodeDefinition']['es'] = "Los Shortcodes sirven para inserir tag, los cuales otorgan funcionalidades especiales. En este caso, el plugin USR provee 2 shortcodes para utilizar en los art&iacute;culos.";
$SETTINGS['NOU']['HowToUSR']['es'] = "Para inserir un Universal Star Ratings en un articulo, escriba <code>[usr=5]</code> donde el 5 indica el numero de estrellas.";
$SETTINGS['NOU']['HowToUSRList']['es'] = "Para inserir una lista de Universal Star Ratings en un articulo, escriba <code>[usrlist &quot;Pizza:3&quot; &quot;Ice Cream:3.5&quot; (...)]</code> donde el primer valor es lo que usted desea evaluar, y el segundo valor el rating. Su lista puede ser tan larga como desee, pero debe consistir de al menos 1 par Key:value.";
$SETTINGS['NOU']['HowToShortCodes']['es'] = "Ambos shortcodes puede ser utilizados con par&aacute;metros para sobrescribir los de default:<li type=square><code>img=&quot;image.name&quot;</code> sobrescribe la imagen de default. Controle que el nombre de la imagen sea correcto!</li><li type=square><code>max=10</code> sobrescribe el valor m&aacute;ximo de estrellas por evaluacion</li><li type=square><code>size=20</code> sobrescribe el valor tama&ntilde;o de la estrella</li><li type=square><code>text=false</code> sobrescribe la opci&oacute;n para el texto base en output (su valor puede ser solo &quot;true&quot; o &quot;false&quot;)</li>El shortcode para inserir una lista de Universal Star Ratings puede ser utilizado tambien con otros parametros:<li type=square><code>avg=true</code> sobrescribe la opci&oacute;n para el c&aacute;lculo del promedio de la evaluaci&oacute;n (su valor puede ser solo &quot;true&quot; o &quot;false&quot;)</li>";

//Settings "Options"
$SETTINGS['OPT']['Options']['es'] = "Opciones";
$SETTINGS['OPT']['ExplainOptions']['es'] = "Estas opciones pueden cambiar el comportamiento y el estilo del plugin <em>Universal Star Rating</em> en su art&iacute;culo.";
$SETTINGS['OPT']['ExplainLanguageSetting']['es'] = "Lenguaje";
$SETTINGS['OPT']['OriginLanguage']['es'] = "Espa&ntilde;ol";
$SETTINGS['OPT']['ExplainStarSizeSetting']['es'] = "Tama&ntilde;o de la estrella";
$SETTINGS['OPT']['ExplainStarCountSetting']['es'] = "N&uacute;mero m&aacute;ximo de estrellas por evaluaci&oacute;n";
$SETTINGS['OPT']['ExplainStarText']['es'] = "Texto";
$SETTINGS['OPT']['ExplainStarImage']['es'] = "Imagen";
$SETTINGS['OPT']['ExplainAverageCalculation']['es'] = "Calcular promedio";
$SETTINGS['OPT']['ExplainPermitShortcodedComment']['es'] = "Habilitar shortcodes en los comentarios";
$SETTINGS['OPT']['ExplainSchemaOrg']['es'] = "Permitir Schema.org <abbr title=\"Search Engine Optimization\">SEO</abbr>";
$SETTINGS['OPT']['ExplainCUSRI']['es'] = "Custom Folder Im&aacute;genes";
$SETTINGS['OPT']['DefaultLanguage']['es'] = "<em>Est&aacute;ndar: <code>English</code></em>";
$SETTINGS['OPT']['DefaultStarSize']['es'] = "<em>Est&aacute;ndar: <code>12</code> (en px)</em>";
$SETTINGS['OPT']['DefaultStarCount']['es'] = "<em>Est&aacute;ndar: <code>5</code>; M&iacute;nimo: 1 (como entero)</em>";
$SETTINGS['OPT']['DefaultStarText']['es'] = "<em>Est&aacute;ndar: <code>Habilitado</code></em>";
$SETTINGS['OPT']['DefaultAverageCalculation']['es'] = "<em>Est&aacute;ndar: <code>Deshabilitado</code></em>";
$SETTINGS['OPT']['DefaultPermitShortcodedComment']['es'] = "<em>Est&aacute;ndar: <code>Deshabilitado</code></em>";
$SETTINGS['OPT']['DefaultSchemaOrg']['es'] = "<em>Est&aacute;ndar: <code>Deshabilitado</code>; Causas de error <a href=\"http://www.w3.org/\" target=\"_blank\">W3</a>!</em>";
$SETTINGS['OPT']['DefaultCUSRI']['es'] = "<em>Est&aacute;ndar: /wp-content/<code>cusri</code></em>";
$SETTINGS['OPT']['DefaultEnabled']['es'] = "Habilitado";
$SETTINGS['OPT']['DefaultDisabled']['es'] = "Deshabilitado";

//Settings "Preview"
$SETTINGS['PREV']['Preview']['es'] = "Anticipo";
$SETTINGS['PREV']['Example']['es'] = "<strong>Ejemplo</strong>";
$SETTINGS['PREV']['ExampleResult']['es'] = "<strong>Resultado</strong>";
$SETTINGS['PREV']['ExampleUsr']['es'] = "Pel&iacute;cula: [usr=3.5]";
$SETTINGS['PREV']['ExampleUsrSize']['es'] = "Pel&iacute;cula: [usr=3.5 size=20]";
$SETTINGS['PREV']['ExampleUsrResult']['es'] = "Pel&iacute;cula: ";
$SETTINGS['PREV']['ExampleUsrList']['es'] = "[usrlist Pizza:3 Helado:3.5 &quot;Leche Agria&quot;]";
$SETTINGS['PREV']['ExampleUsrListResult']['es'][1] = "Pizza:3";
$SETTINGS['PREV']['ExampleUsrListResult']['es'][2] = "Helado:3.5";
$SETTINGS['PREV']['ExampleUsrListResult']['es'][3] = "Leche Agria";
$SETTINGS['PREV']['ExampleUsrOverriddenImage']['es'] = "Pel&iacute;cula: [usr=3.5 img=&quot;03.png&quot;]";
$SETTINGS['PREV']['ExampleUsrOverriddenText']['es'] = "Pel&iacute;cula: [usr=3.5 text=&quot;false&quot;]";
$SETTINGS['PREV']['ExampleUsrOverriddenMax']['es'] = "Pel&iacute;cula: [usr=3.5 max=&quot;3&quot;]";
$SETTINGS['PREV']['ExampleUsrOverriddenAll']['es'] = "Pel&iacute;cula: [usr=3.5 max=&quot;3&quot; text=&quot;false&quot; img=&quot;03.png&quot; size=20]";
$SETTINGS['PREV']['ExampleUsrListOverriddenAverage']['es'] = "[usrlist Pizza:3 Helado:3.5 &quot;Leche Agria&quot; avg=&quot;true&quot;]";

?>