/**
	Validates to make sure an email has something in the format of string@string
*/
function emailValidator() {
    // return true if contains string after '@' symbol, false otherwise
    this.containsSuffix = function(email) {
        var symbolIndex = email.indexOf('@');
        if (email.charAt(symbolIndex + 1) !== '') {
            return true;
        }
        return false;
    },
    
    // return true if contains string before '@' symbol, false otherwise
    this.containsPrefix = function(email) {
        var symbolIndex = email.indexOf('@');
        if (symbolIndex > 0) {
            return true;
        }
        return false;
    },
    
    // return true if contains '@' symbol, false otherwise
    this.containsAtSymbol = function(email) {
        var symbolIndex = email.indexOf('@');
        if (symbolIndex >= 0) {
            return true;
        }
        return false;
    },
    
	// returns true if contains no spaces
    this.hasNoSpaces = function(email) {
        if (email.indexOf(' ') < 0) {
            return true;
        }
        return false;
    }
    
    // return true if valid, false otherwise
    this.validateEmail = function(email) {
        if (this.containsAtSymbol(email) && this.containsPrefix(email) && this.containsSuffix(email) && this.hasNoSpaces(email)) {
            return true;
        }
        return false;
    }
}
