var links = [];
var casper = require('casper').create();
var merchantid = casper.cli.get(0);
var indexField = casper.cli.get(1);

function getLinks() {
    var links = document.querySelectorAll('ul.column a');
    return Array.prototype.map.call(links, function(e) {
        return e.getAttribute('href');
    });
}

casper.start();
casper.userAgent("Mozilla/5.0 (Windows NT 6.1;en-US) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.63 Safari/537.36");
casper.open('http://www.amazon.com/gp/search/other/ref=sr_sa_p_4?rh=i%3Amerchant-items&pickerToList=brandtextbin&me='+ merchantid + '&indexField=' + indexField).viewport(1024, 690).then(function() {
});

casper.then(function() {
    links = this.evaluate(getLinks);
});


casper.run(function() {
    this.echo(links.join('\n'));
    this.exit();
});