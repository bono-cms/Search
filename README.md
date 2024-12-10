# Search

This module allows you to add search functionality to your site. Modules that support search functionality are attached automatically, so no action is required on your part.
Before using it, you need to create a view template called `search.phtml` in your current theme directory.

## Using with Widgets

Widgets are the recommended way to use the search module. The default widget is Offcanvas, which renders a search form inside it.

Render a widget in your layout file, first:

    <?php
    
    use Search\View\Widget\OffcanvasWidget;
    
    $searchWidget = $this->widget(new OffcanvasWidget([
        'text' => '<i class="bi bi-search"></i>', // Text inside button
        'btnClass' => 'btn btn-primary' // Button class
    ]));
    
    ?>
    
    <!-- Somewhere in your layout file before a header -->
    <?= $searchWidget['offcanvas']; ?>
    
    <header>
        <nav>
            <!-- Somewhere in your navigation bar Renders a button which triggers Offcanvas widget -->
            <?= $searchWidget['button']; ?>
        </nav>
    </header>

You're almost done. Now you need to handle response. For that, open `search.phtml` and add the following initialization code:

    <?php
    
    use Krystal\Widget\Pagination\PaginationWidget;
    use Search\View\Widget\ResponseWidget;
    
    ?>
    
    <section class="bg-light py-5 my-5">
        <div class="container">
            <?= $this->widget(new ResponseWidget(
                isset($results) ? $results : [],
                isset($errors) ? $errors : [], [
                    'count' => isset($paginator) ? $paginator->getTotalAmount() : 0,
                    'keyword' => $search->getKeyword(),
                    'items' => [
                        'link_class' => 'text-dark text-decoration-none'
                    ]
            ])); ?>
    
            <?php if (isset($paginator)): ?>
            <nav>
                <?= $this->widget(new PaginationWidget($paginator)); ?>
            </nav>
            <?php endif; ?>
        </div>
    </section>

> **TIP:** You can use shared partial to render pagination.

## Using without Widgets

Sometimes, you might want to build your own custom user interface, where default widgets do not fit.

The module has one service, which is called `$search` with following methods:

    $search->getUrl() // Returns URL where all search request must be send to
    $search->getElementName() // Returns a name of an element query's text should come from
    $search->getKeyword() // Returns current keyword typed by user

### Template variables

There are 2 pre-defined variables for search template:

#### $paginator

Since search results can be paginated, `$paginator` service contains relevant information.

#### $errors

An array of error messages
 
### Example: Typical fragment in layout

    <form action="<?php echo $search->getUrl(); ?>">
       <input type="text" name="<?= $search->getElementName(); ?>" value="<?= $search->getKeyword(); ?>" />
       <button type="submit"><?= $this->translate('Search'); ?></button>
    </form>

 

#### Example: Building the structure for search's template

The content in `search.phtml` goes like this:

    <?php
    
    use Krystal\Widget\Pagination\PaginationWidget;
    
    ?>
    
    <section class="bg-light py-5">
        <div class="container">
          <?php if (empty($errors)): ?>
          <div class="mb-5">
             <h3 class="text-muted"> <?= $this->translate('Search results for'); ?> "<?= $search->getKeyword(); ?>" (<?= $paginator->getTotalAmount(); ?>)</h3>
          </div>
    
          <?php if (!empty($results)): ?>
          <?php foreach ($results as $result): ?>
          <div class="mb-4 p-4 bg-white shadow">
            <p><a class="fw-bold text-decoration-none" href="<?= $result->getUrl(); ?>"><?= $result->getTitle(); ?></a></p>
            <div>
                <?= $result->getContent(); ?>
            </div>
          </div>
          <?php endforeach; ?>
          
          <?php if (isset($paginator)): ?>
            <nav>
                <?= $this->widget(new PaginationWidget($paginator)); ?>
            </nav>
          <?php endif; ?>
          
          <?php else: ?>
    
          <div class="text-center">
            <h2 class="text-muted"><?= $this->translate('No results'); ?></h2>
          </div>
          
          <?php endif; ?>
          
          <?php else: ?>
    
          <ul class="list-unstyled">
             <?php foreach ($errors as $error): ?>
             <li class="text-muted"><?= $error; ?></li>
             <?php endforeach; ?>
           </ul>
          <?php endif; ?>
        </div>
    </section>

> **TIP:** You can use shared partial to render pagination.