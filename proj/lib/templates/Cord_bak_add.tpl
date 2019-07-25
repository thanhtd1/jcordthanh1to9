{
	"head":{
{include file='Cord_bak_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='Cord_bak_item.tpl'}
{/if}
{/section}
	}
}
