function activeMenuItem()
{
    let menuItems=document.querySelectorAll('.menu-item');
    menuItems.forEach(el => {
        el.addEventListener("click",()=>{
            if(!el.classList.contains('active'))
            {
                hideEl=document.querySelector('.menu-item.active')
                if(hideEl!=null)
                {
                    hideEl.classList.remove('active');
                    let hideContent=document.getElementsByClassName(hideEl.dataset.value)[1];
                    hideContent.classList.remove('active');
                }

                el.classList.add('active');
                let content=document.getElementsByClassName(el.dataset.value)[1];
                if(content!=null)
                {
                    content.classList.add('active');
                }
            }
        });
    });
}
