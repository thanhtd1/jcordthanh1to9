!function(r){"use strict";new Card({form:".active form",container:".card-wrapper"}),r("#owner");var e=r("#cardNumber"),a=r("#card-number-field"),s=r("#cvv"),d=r("#mastercard"),t=r("#confirm-purchase"),n=r("#visa"),p=r("#amex");e.payform("formatCardNumber"),s.payform("formatCardCVC"),e.keyup(function(){p.removeClass("transparent"),n.removeClass("transparent"),d.removeClass("transparent"),0==r.payform.validateCardNumber(e.val())?a.addClass("has-error"):(a.removeClass("has-error"),a.addClass("has-success")),"visa"==r.payform.parseCardType(e.val())?(d.addClass("transparent"),p.addClass("transparent")):"amex"==r.payform.parseCardType(e.val())?(d.addClass("transparent"),n.addClass("transparent")):"mastercard"==r.payform.parseCardType(e.val())&&(p.addClass("transparent"),n.addClass("transparent"))}),t.on("click",function(a){a.preventDefault();r.payform.validateCardNumber(e.val()),r.payform.validateCardCVC(s.val())})}(jQuery);