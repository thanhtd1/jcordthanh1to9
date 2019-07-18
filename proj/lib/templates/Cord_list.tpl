{
	"head":{
{include file='Cord_head.tpl'}
	},
	"data":{
		"count":{count($data)},
		"entries":[
{section name=no loop=$data}
			{
{include file='Cord_item.tpl'}
{if $smarty.section.no.last}
			}
{else}
			},
{/if}
{/section}
		]
	}
}
