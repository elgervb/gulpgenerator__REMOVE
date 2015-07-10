/**
 * API for:
 * interface Storage {
 *  readonly attribute unsigned long length;
 *  DOMString? key(unsigned long index);
 *  getter DOMString? getItem(DOMString key);
 *  setter creator void setItem(DOMString key, DOMString value);
 *  deleter void removeItem(DOMString key);
 *  void clear();
 * };
 */
app.service('StorageService', function() {

  var storage = window.localStorage;

  var get = function(key) {return angular.fromJson(storage.getItem(key));}, 
  set = function(key, value) { storage.setItem(key, angular.toJson(value)); return this; }, 
  key = function(index) {return storage.key(index);}, 
  remove = function(key) {storage.removeItem(key); return this;}, 
  clear = function() {storage.clear(); return this;},
  create = function(key) {
    var _clear = function() {
      clear(); return this;
    },
    _get = function() {
      return get(key);
    },
    _set = function(value) {
      set(key, value); return this;
    },
    _remove = function() {
      remove(key); return this;
    };

    return {
      clear: _clear,
      get: _get,
      key: key,
      lenght: storage.length,
      set: _set,
      remove: _remove
    };
  };

  return {
    clear: clear,
    create: create,
    get: get,
    key: key,
    length: storage.length,
    set: set,
    remove: remove
  };
});
