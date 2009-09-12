{*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 
Copyright (C) 2007 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*}

{include file="header.tpl"}

<h1><img src="themes/ffclrp/images/logo.png" alt="CoTeia - Web Based Collaborative Edition Tool" />CoTeia</h1>
<hr align="right" />

<h2>Active wikis:</h2>

{if count($currentWikis) == 0}
<em>(There is no currently active wikis).</em>
{else}
<ul>
	{foreach from=$currentWikis item=wiki}
	<li>
		<div class="swiki">
			<span class="swiki-title"><a href="index.php?do=View&type=Wiki&name={$wiki->id}">{$wiki->name|escape}</a></span>
			(Admin: <a href="mailto:{$wiki->maintainerEmail|escape}">{$wiki->maintainer|escape}</a>)
		</div>
	</li>
	{/foreach}
</ul>
{/if}

{if count($otherWikis) != 0}
<h2>Old (and probably innactive) wikis:</h2>
<ul>
{foreach from=$otherWikis item=wiki}
	<li>
		<div class="swiki">
			<span class="swiki-title"><a href="index.php?do=View&type=Wiki&name={$wiki->id}">{$wiki->name|escape}</a></span>
			(Admin: <a href="mailto:{$wiki->maintainerEmail|escape}">{$wiki->maintainer|escape}</a>)
		</div>
	</li>
{/foreach}
</ul>
{/if}

{include file="footer.tpl"}