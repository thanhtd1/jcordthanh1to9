{
	"head":{
{include file='Bank_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='Bank_item.tpl'}
{/if}
{/section}
	}
}
