 Instalación de ritho-web
===========================

Este documento explica todos los pasos necesarios para instalar
y configurar ritho-web. El documento está estructurado en las
siguientes secciones:

1.- Requisitos de instalación: En esta sección describiremos los
requisitos hardware y software para instalar la aplicación.
2.- Proceso de instalación: Esta sección explica todos los pasos
necesarios para instalar la aplicación de forma satisfactoria.
3.- Primeros pasos: Esta sección muestra algunos ejemplos iniciales
para empezar a usar la aplicación.

 Requisitos de instalación
===========================

 Proceso de instalación
========================

Opciones para Apache:

Options +FollowSymLinks
RewriteEngine On
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteRule .* index.php [L] 

 Primeros pasos
================
