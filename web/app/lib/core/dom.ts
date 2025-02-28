type NodeElement = {
    self : Element,
    children?: Array<NodeElement>
};

class ParentDOM{
    root ?: NodeElement;
    private static rootNode ?: ParentDOM;
    private constructor(rootParent : HTMLElement){
	this.root = {
	    self : rootParent,
	    children : []
	};
    }
    public static instance(root = document.body){
	if(ParentDOM.rootNode === null)
	    ParentDOM.rootNode = new ParentDOM(root);
	return ParentDOM.rootNode;
    }
    public changeStateOn(element : Element){
	element
    }
}
