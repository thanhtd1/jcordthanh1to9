{
	"head":{
{include file='Cord_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='Cord_item.tpl'}
{/if}
{/section}
	}
}
