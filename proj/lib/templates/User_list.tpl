{
	"head":{
{include file='User_head.tpl'}
	},
	"data":{
		"count":{count($data)},
		"entries":[
{section name=no loop=$data}
			{
{include file='User_item.tpl'}
{if $smarty.section.no.last}
			}
{else}
			},
{/if}
{/section}
		]
	}
}
