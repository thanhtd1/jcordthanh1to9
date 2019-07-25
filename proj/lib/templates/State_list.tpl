{
	"head":{
{include file='State_head.tpl'}
	},
	"data":{
		"count":{count($data)},
		"entries":[
{section name=no loop=$data}
			{
{include file='State_item.tpl'}
{if $smarty.section.no.last}
			}
{else}
			},
{/if}
{/section}
		]
	}
}
