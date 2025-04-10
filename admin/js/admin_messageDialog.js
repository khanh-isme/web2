let messageDialog=document.querySelector('#messageDialog');
function showMessageDialog(message)
{
    messageDialog.innerHTML=message;
    messageDialog.style.animation='none';
    messageDialog.offsetHeight;
    messageDialog.style.animation='showMessageDialog 5s ease forwards';
}
