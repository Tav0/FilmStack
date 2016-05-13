$(document).ready(function() {
    console.log('hi from search.js');
    
    var getCurrentLowestPageNumber = function(obj) {
        var ulParent = obj.parent().parent();
        //console.log(ulParent);

        var pageLinks = ulParent.find('.pageLink');
        //console.log(pageLinks);
        
        var firstPageLink = $(pageLinks[0]);
        //console.log(lastPageLink);
        
        var firstPageLinkPageNumber = firstPageLink.attr('data-page-number');
        //console.log(lastPageLinkPageNumber);
        
        return firstPageLinkPageNumber;
    };
    
    var getCurrentHighestPageNumber = function(obj) {
        var ulParent = obj.parent().parent();
        //console.log(ulParent);

        var pageLinks = ulParent.find('.pageLink');
        //console.log(pageLinks);
        
        var lastPageLink = $(pageLinks[pageLinks.length - 1]);
        //console.log(lastPageLink);
        
        var lastPageLinkPageNumber = lastPageLink.attr('data-page-number');
        //console.log(lastPageLinkPageNumber);
        
        return lastPageLinkPageNumber;
    };
    
    var getPrevListOfPageNumbers = function(currentLowestPageNumber) {
        var currentLowestPageNumber = parseInt(currentLowestPageNumber);
        
        var to = currentLowestPageNumber - 1;
        var from = to - 10 + 1;
        
        var list = Array();
        for (i = from; i <= to; i++) {
            list.push(i);
        }
        
        return list;
    }
    
    var getNextListOfPageNumbers = function(currentHighestPageNumber, numTotalPages) {
        var currentHighestPageNumber = parseInt(currentHighestPageNumber);
        var numTotalPages = parseInt(numTotalPages);
        
        var from = currentHighestPageNumber + 1;
        var to = currentHighestPageNumber + 10;
        if (to > numTotalPages) {
            to = numTotalPages;
        }
        
        var list = Array();
        for (i = from; i <= to; i++) {
            list.push(i);
        }
        
        return list;
    };
    
    var redrawPagination = function(listOfPageNumbers, numTotalPages) {
        console.log('LIST OF PAGE NUMBERS');
        console.log(listOfPageNumbers);
        
        var len = listOfPageNumbers.length;
        var searchTerm = $('#searchTerm').attr('data-search-term');
        
        var newPaginationHtml = '<ul class="pagination">';
        for (i = 0; i < len; i++) {
            if (i == 0) {
                if (listOfPageNumbers[0] != 1) {
                    newPaginationHtml += '<li><a href="#" aria-label="Previous" class="prevSetOfPages"><span aria-hidden="true">&laquo;</span></a></li>';
                }
            }
            var hrefHtml = BASE_URL + '/search/' + searchTerm + '/' + listOfPageNumbers[i];
            newPaginationHtml += "<li><a href='" + hrefHtml + "' class='pageLink' data-page-number='" + listOfPageNumbers[i] + "'>" + listOfPageNumbers[i] + "</a></li>";
        }
        if (listOfPageNumbers[len - 1] != numTotalPages) {
            newPaginationHtml += '<li><a href="#" aria-label="Next" class="nextSetOfPages"><span aria-hidden="true">&raquo;</span></a></li>';
        }
        newPaginationHtml += '</ul>';
        
        //$('body').append(newPaginationHtml);
        $('.pagination').parent().html(newPaginationHtml);
        
        runPrevSetOfPagesHandler();
        runNextSetOfPagesHandler();
    };
    
    var runPrevSetOfPagesHandler = function() {
       $('a.prevSetOfPages').click(function(event) {
           
            event.preventDefault();
            console.log('PREV set of pages link was clicked');
            
            var currentLowestPageNumber = getCurrentLowestPageNumber($(this));
            console.log('currentLowestPageNumber');
            console.log(currentLowestPageNumber);
            
            var prevListOfPageNumbers = getPrevListOfPageNumbers(currentLowestPageNumber);
            console.log('prevListOfPageNumbers');
            console.log(prevListOfPageNumbers);
            
            var numTotalPages = $('#totalPages').attr('data-total-pages');
            redrawPagination(prevListOfPageNumbers, numTotalPages);
       }); 
    };
    
    var runNextSetOfPagesHandler = function() {
        $('a.nextSetOfPages').click(function(event) {
            event.preventDefault();
            console.log('it was clicked');
            
            var currentHighestPageNumber = getCurrentHighestPageNumber($(this));
            console.log('CURRENT HIGHEST PAGE NUMBER');
            console.log(currentHighestPageNumber);
            
            var numTotalPages = $('#totalPages').attr('data-total-pages');
            var nextListOfPageNumbers = getNextListOfPageNumbers(currentHighestPageNumber, numTotalPages);
            console.log(nextListOfPageNumbers);
            
            redrawPagination(nextListOfPageNumbers, numTotalPages);
        });
    };

    runPrevSetOfPagesHandler();
    runNextSetOfPagesHandler();
});
