{if count($head) == 0}
		"status":"SUCCEEDED"
		,"message":"succeeded"
{else}
		"status":"FAILED"
		,"message":"failed"
		,"reasons":[
{section name=no loop=$head}
	 	 {
			"what":"{$head[no].what}"
			,"how":"{$head[no].how}"
			,"why":"{$head[no].why}"
			,"level":"{$head[no].level}"
		}
{if $smarty.section.no.last}
{else}
		,
{/if}
{/section}
		]
{/if}
