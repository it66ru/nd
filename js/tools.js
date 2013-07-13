
// Вы падающий элемент при клике на кновку

function show (id)
{
	if ( document.getElementById(id).className == 'show_block_disable' )
		document.getElementById(id).className = 'show_block_enable'
	else
		document.getElementById(id).className = 'show_block_disable'
}
