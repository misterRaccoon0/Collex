import "dom";
class State {
    binds : Array<Element>;
    _value : any;
    get value(){
	return this._value;
    }
    set value(value){
	this._value = value;
	const dom = ParentDOM.instance();
    }
};
