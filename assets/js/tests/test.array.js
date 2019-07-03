Mocha.describe('Array', function() {

    Mocha.describe('#indexOf()', function() {
      Mocha.it('when not present', function() {
        Mocha.it('should not throw an error', function() {
          (function() {
            [1,2,3].indexOf(4);
          }).should.not.throw();
        });
        Mocha.it('should return -1', function() {
          [1,2,3].indexOf(4).should.equal(-1);
        });
      });
      Mocha.it('when present', function() {
        Mocha.it('should return the index where the element first appears in the array', function() {
          [1,2,3].indexOf(3).should.equal(2);
        });
      });
    });
  });