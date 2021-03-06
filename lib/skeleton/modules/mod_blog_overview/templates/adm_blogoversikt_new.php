<?php

/*******************************************************************************
The contents of this file are subject to the Mozilla Public License
Version 1.1 (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/

Software distributed under the License is distributed on an "AS IS"
basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
License for the specific language governing rights and limitations
under the License.

The Original Code is (C) 2004-2010 Blest AS.
New code is (C) 2011 Idéverket AS, 2015 Friend Studios AS

The Initial Developer of the Original Code is Blest AS.
Portions created by Blest AS are Copyright (C) 2004-2010
Blest AS. All Rights Reserved.

Contributor(s): Hogne Titlestad, Thomas Wollburg, Inge Jørgensen, Ola Jensen, 
                Rune Nilssen
*******************************************************************************/


?>
		<h1>
			Oversikt over publiserte blogger
		</h1>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<h2>
				Velg hvilke blogger som skal med i oversikten:
			</h2>
		</div>
		<div class="SpacerSmall"></div>
		<div id="mod_blogoverview_list" class="SubContainer">
			<table>
				<tr>
					<th>
						Blognavn:
					</th>
					<th>
	   					Antall viste:
	   				</th>
	   				<th>
	   					Sidenavigasjon:
	   				</th>
	   				<th>
	   					Utlistingsheading:
	   				</th>
	   			</tr>
				<tr>
<?
    $db =& dbObject::globalValue('database');

    list ( , $mixed ) = explode ( '<!--Version 2.0-->', $this->datamixed );
	$this->globalConf = false;
	$this->conf = array ();
	if ( $mixed = explode ( '<!--separate-->', $mixed ) )
	{
		$i = 0;
		foreach ( $mixed as $mix )
		{
			if ( $i++ == 0 )
			{
				$this->globalConf = CreateObjectFromString ( $mix );
			}
			else
			{
				$this->conf[] = CreateObjectFromString ( $mix );
			}
		}
    }

    $q = 'SELECT c.* from ContentDataSmall e, ContentElement c WHERE e.DataString = "mod_blog" AND c.ID = c.mainID AND e.ContentID = c.mainID';

   	$str = '';
   	$num = 1;
    if ( $blogpages = $db->fetchObjectRows($q) )
    {
        foreach ( $blogpages as $key=>$blogpage )
        {
        	$hit = false;
        	$nav = 'no';
        	$selected = false;
        	$amount = 4;
        	$title = '';
        	foreach ( $this->conf as $cnf )
        	{
        		if ( $cnf->activated != $blogpage->MainID )
        			continue;
		        $hit = false;
		        $nav = $cnf->navigation;
	            $hit = true;
	            $selected = true;
	            $amount = $cnf->quantity;
	            $title = $cnf->heading;
	            break;
	        }
		        
	        $str.= '
		        	<tr>
		        		<td>
		        			<input type="checkbox"' . ( $hit ? ' checked="checked"' : '' ) . ' id="blog_' . $blogpage->MainID . '">
		        			<span>' . $blogpage->Title . '   </span>
		        		</td>
		        		<td>
		        			<input type="text" id="blog_amount_' . $blogpage->MainID . '" class="BlogAntall" style="width: 30px" size="3" value="' . $amount . '">
		        		</td>
		        		<td>
				    		<select id="navigateselect' . $blogpage->MainID . '" name="navigate">
				    			<option ' . ($nav == 'on' ? 'selected ' : '') . 'value="on">P&aring</option><option ' . ($nav == 'off' ? 'selected ' : '') . 'value="off">Av</option>
				    		</select>
				    	</td>
				    	<td>
				    		<input type="text" value="' . trim ( $title ) . '" style="text-align: left" name="title_' . $blogpage->MainID . '">
				    	</td>
		        	</tr>
			';
        }
    }
    
        
    return $str;
?>
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<h2>
				<?= i18n ( 'blogoverview_List_mode' ) ?>:
			</h2>
		</div>
		<div class="SpacerSmall"></div>
		<div class="SubContainer">
			<select id="mod_blog_listmode">
				<?
					$listmode = $this->globalConf->listmode;
					$this->sizex = $this->globalConf->leadinimagewidth;
					$this->sizey = $this->globalConf->leadinimageheight;
					$str = '';
					foreach ( array ( 'titles', 'titles_images', 'full' ) as $mode )
					{
						$sel = $mode == $listmode ? ' selected="selected"' : '';
						$str .= '<option value="' . $mode . '"' . $sel . '>' . i18n ( 'blogoverview_mode' . $mode ) . '</option>';
					}
					return $str;
				?>
			</select>
		</div>
		<div class="SpacerSmallColored"></div>
		<div class="Container" style="padding: <?= MarginSize ?>px">
			<h2>
				<?= i18n ( 'blogoverview_Image_sizes' ) ?>:
			</h2>
		</div>
		<div class="SpacerSmall"></div>
		<div class="SubContainer">
			<table>
				<tr>
					<td>
						<?= i18n ( 'blogoverview_width' ) ?>: <input type="text" size="6" id="mod_blog_sizex" value="<?= $this->sizex ? $this->sizex : '0' ?>"/><br/>
					</td>
					<td>
						<?= i18n ( 'blogoverview_height' ) ?>: <input type="text" size="6" id="mod_blog_sizey" value="<?= $this->sizey ? $this->sizey : '0' ?>"/>
					</td>
				</tr>
			</table>
		</div>
		<div class="SpacerSmallColored"></div>
		<button type="button" onclick="mod_blog_overview_save()">
			<img src="admin/gfx/icons/page_go.png"> <span id="mod_blog_saveblog">Lagre blogoversikt</span>
		</button>

		<button onclick="updateStructure ( ); removeModalDialogue ( 'blogoversikt_new' )" type="button">
			<img src="admin/gfx/icons/cancel.png"/> Lukk
		</button>

