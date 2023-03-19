(function($){

   
        class post_pagination{
            constructor(action, posts_per_page,ajaxURL,nonce,element) 
              {
                
                if(!element)
                {
                    return console.log('pagination error: div container cant be found');
                }
                this.element = element;
                this.url = ajaxURL+'?action='+action+'&nonce='+nonce+'&post_per_page='+posts_per_page;
             
                post_pagination.loadResponse(this.url,this.element);
                  
              }

  

           static loadResponse(url,element)
                {
        
                    $.get(url,function(response){
                        element.html(response.post);
                        element.css({'pointer-events':'all','opacity':'1'});
            
                        element.find('a.page-numbers').click(function(e)
                            {
                              
                                    e.preventDefault();
            
                                    
                                    element.css({'pointer-events':'none','opacity':'0.5'});
                                    var url2 = $(this).attr('href');
                                    if(url2)
                                    {
                                        post_pagination.loadResponse(url2,element);
                                    }
                            });
            
                        });
                    
                }
  

        }

        var container = $('#post-with-pagination')
        var action = container.attr('data-action');
        var posts_per_page = container.attr('data-postsperpage');

        var rm_posts = new post_pagination(action,posts_per_page,paginationObj.ajaxURL,paginationObj.nonce,container); 


        var container = $('#videos-with-pagination')
        var action = container.attr('data-action');
        var posts_per_page = container.attr('data-postsperpage');

        var rm_videos = new post_pagination(action,posts_per_page,paginationObj.ajaxURL,paginationObj.nonce,container); 
    
})(jQuery);