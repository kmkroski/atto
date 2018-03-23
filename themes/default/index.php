<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $this->config['title'] ?></title>
    <meta name="description" content="<?= $this->config['description'] ?>">
    <meta name="author" content="<?= isset($this->config['author']) && isset($this->config['author']['name']) ? $this->config['author']['name'] : '' ?>">

    <link rel="stylesheet" type="text/css" href="<?= $this->config['base_url'] ?>css/app.css">
</head>
<body>
    <div class="container">
        <header>
            <img src="<?= $this->config['base_url'] ?>images/profile.jpg" alt="<?= $this->config['title'] ?>">
            <h1>
                <a href="<?= $this->config['base_url'] ?>">
                    <?= $this->config['title'] ?>
                </a>
            </h1>
            <span class="byline"><?= $this->config['description'] ?></span>
        </header>

        <main>
            <div id="loadingBlock">
                <article>
                    Loading...
                </article>
            </div>

            <div id="postsBlock" style="display: none;"></div>

            <div class="button-block" id="pagingBlock">
                <a class="prev button" href="/">New Posts...</a>
                <a class="next button" href="/">Older Posts...</a>
            </div>

            <div class="button-block" id="navBlock" style="display: none;">
                <a class="back button" href="/">Back to Posts...</a>
            </div>
        </main>

        <footer>
            &copy; <?= date('Y') ?> <?= isset($this->config['author']) && isset($this->config['author']['name']) ? $this->config['author']['name'] : '' ?>
            &bull; <a href="<?= $this->config['base_url'] ?>data/feeds/1.json">JSON Feed
        </footer>
    </div>

    <script type="text/template" id="postTemplate">
        <article>
            <h1>
                <a href="{{PERMALINK}}">{{TITLE}}</a>
            </h1>
            {{CONTENT}}
            <span class="date">{{DATE}}</span>
        </article>
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript">
        window.base_url = "<?= $this->config['base_url'] ?>";
    </script>
    <script src="<?= $this->config['base_url'] ?>js/app.js"></script>
</body>
</html>
