<div class="content-wrapper" style="min-height: 650px;">  
    <section class="content-header">
        <h1>
            ICCA Panel
            <small>v1.0</small>
            
        </h1>
        
        <ol class="breadcrumb" >
            <li><a href="?page=home"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">
                <?php
                    $page_title = array(
                        ''=>'',
                        'home'=>'Dashboard',
                        'profile' => 'Profile',
                        'createArticleNew' => "New Article",
                        'editArticle'=> "Edit Article",
                        'rewriteArticleNew' => "Rewrite",
                        'rewritingNew' => "Rewriting Article",
                        
                        'rewritten-sentences' => "Rewritten Sentences",
                        'rewritten-sentence' => "Rewrite Details",
                        'assignments' => "Assignments",
                        'export-article' => "Export Article",
                        'credentials' => "API Credentials",
                        'article-view' => "Article Details",
                        'importArticle'=> "Import Article",
                        'text-tool' => "Tool",
                        'performanceReport' => "Reports",
                        'category' => "Article Category",
                        'priority' => "Priority",
                        'export-article2' => "Export By Category"
                    );
                    echo $page_title[$page];
                ?>
            </li>
        </ol>
    </section>
    
    <div>
       <?php
            switch ($page) {
                case 'home':
                   include 'views/home/index.php';
                break;
                case 'profile':
                   include 'views/profile/create.php';     
                break;                
                default:
                   include 'views/'.$page.'.php';
                break;
            }
       ?> 
    </div>
</div>   
