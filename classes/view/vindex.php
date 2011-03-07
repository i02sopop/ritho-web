<?php
/* This file is part of ritho-web.

   ritho-web is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as 
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   ritho-web is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public
   License along with Foobar. If not, see <http://www.gnu.org/licenses/>.
*/

/*
  View for the Index table.

  @author Ritho-web team
  @copyright Copyright (c) 2011 Ritho-web team (look at AUTHORS file)
*/
class VIndex extends View {
  /*
    Constructor of VIndex.

    @parameter url (string): Url of the view. It can be a local php file or an external url.
    @parameter context (array): Context variables of the view.
    @parameter action (const string): Action to do: render or redirect.
  */
  public function __construct($name='index', $context=array(), $action=View::RENDER_ACTION) {
    $parent::__construct($name, $context=array(), $action=View::RENDER_ACTION);
  }

  /*
    Method to generate the output the view.
  */
  public function render() {
    $index = new Template($this->name);
    $index->jquery = '/js/ritho.js';

    $index->head = new Template('head');
    $index->head->charset = 'utf-8';
    $index->head->author = 'Pablo Alvarez de Sotomayor Posadillo';
    $index->head->description = 'Ritho\'s web page. It includes all the projects, blogs, new, ...';
    $index->head->copy = 'Copyright 2011, Pablo Alvarez de Sotomayor Posadillo';
    $index->head->projName = 'Ritho';
    $index->head->creator = 'Pablo Alvarez de Sotomayor Posadillo';
    $index->head->subject = 'Ritho\'s web page. It includes all the projects, blogs, new, ...';

    //size: 16x16 or 32x32, transparency is OK, see wikipedia for info on broswer support: http://mky.be/favicon/
    $index->head->favicon = '/img/favicon.png';

    // size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
    // To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
    // Transparency is not recommended (iOS will put a black BG behind the icon)
    $index->head->appleicon = '/img/custom_icon.png';
    $index->head->css = '/css/style.css';
    $index->head->cssPrint = '/css/stylePrint.css';
    $index->head->cssIE = '/css/styleIE.css';
    $index->head->cssIE7 = '/css/styleIE7.css';
    $index->head->cssIEOld = '/css/styleIEOld.css';
    $index->head->jquery = '/js/jquery.js';
    $index->head->title = $this->name.' - Ritho\'s Web Page';
    $index->head->modernizr = '/js/modernizr-1.7.min.js';
    $index->head->gsVerification = 'Hr_OWj4SMe2RICyrXgKkj-rsIe-UqF15qtVk579MITk';
      
    $index->header = new Template('header');
    $index->left = new Template('left');
    $index->center = new Template('center');
    $index->right = new Template('right');
    $index->footer = new Template('footer'); 
      
    $index->render(true);
  }
}
?>
