{
	"head":{
{include file='Person_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='Person_item.tpl'}
{/if}
{/section}
	}
}
