{
	"head":{
{include file='User_head.tpl'}
	},
	"data":{
{section name=no loop=$data}
{if $smarty.section.no.first}
{include file='User_item.tpl'}
{/if}
{/section}
	}
}
