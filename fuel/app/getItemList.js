var links = [];
var resultCount = [];
var casper = require('casper').create();
var url = casper.cli.get(0);

function getLinks() {
    var links = document.querySelectorAll('div.celwidget');
    return Array.prototype.map.call(links, function(e) {
        return e.getAttribute('name');
    });
}
function getResultCount() {
    var links = document.querySelectorAll('h2.resultCount span');
    return Array.prototype.map.call(links, function(e) {
        return e.innerHTML;
    });
}

casper.start();
casper.userAgent("Mozilla/5.0 (Windows NT 6.1;en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.63 Safari/537.36");
casper.open(url).viewport(1024, 690).then(function() {
});

casper.then(function() {
    links = this.evaluate(getLinks);
    resultCount = this.evaluate(getResultCount);
});


casper.run(function() {
    this.echo(resultCount);
    this.echo(links.join('\n'));
    this.exit();
});