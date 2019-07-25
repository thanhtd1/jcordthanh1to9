{
	"head":{
{include file='System_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='System_item.tpl'}
{/if}
{/section}
	}
}