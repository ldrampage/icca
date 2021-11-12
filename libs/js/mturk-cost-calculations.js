let modalInfo = {
    totalFee: '0.00',
    feePerAssign: '0.00'
}

function isEmpty(value) {
    return (value == null || value == '' || parseFloat(value).toFixed(2) == 0.00);
}

function getModalInfo() {
    return modalInfo;
}

function generateCostSummary(rewardAmt, numOfAssignments, balance) {
    let result = {
        rewardAmount: '$0.00',
        numOfAssign: '0',
        totalReward: '$0.00 x 0 = <b>$0.00</b>',
        mTurkFee: '$0.00',
        totalCost: '$0.00 + $0.00 = <b>$0.00</b>',
        initBal: '$' + parseFloat(balance).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'),
        remainingBal: '$' + parseFloat(balance).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'),
    };
    
    //check if rewardAmt is null, empty, or 0
    if(isEmpty(rewardAmt)) {
        if(!isEmpty(numOfAssignments)) {
            result.numOfAssign = numOfAssignments;
            
            //minimum fee is $0.01 per assignment (even if reward is $0.00)
            let fee_and_cost = parseInt(numOfAssignments) * 0.01; 
            
            result.mTurkFee = '$' + parseFloat(fee_and_cost).toFixed(2);
            result.totalCost = '$0.00 + ' + parseFloat(fee_and_cost).toFixed(2) + ' = <b>$' + parseFloat(fee_and_cost).toFixed(2) + '</b>'; 
            
            //update modal info
            modalInfo.totalFee = parseFloat(fee_and_cost).toFixed(2);
            modalInfo.feePerAssign = parseFloat(fee_and_cost / parseInt(numOfAssignments)).toFixed(2);
        }
    } else {
        result.rewardAmount = '$' + parseFloat(rewardAmt).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'); 
        
        if(isEmpty(numOfAssignments)) {
            result.totalReward = '$' + parseFloat(rewardAmt).toFixed(2) + ' x 0 = <b>$0.00</b>'; 
        } else {
            result.numOfAssign = numOfAssignments;
            
            let total_reward = parseFloat(rewardAmt).toFixed(2) * parseInt(numOfAssignments);
            result.totalReward = '$' + parseFloat(rewardAmt).toFixed(2) + ' x ' + parseInt(numOfAssignments) + ' = <b>$' + parseFloat(total_reward).toFixed(2) + '</b>'; 
            
            //if num_assignments >= 10, additional 20% (total mturk fee is 40%)
            let percent = 0;
            if(parseInt(numOfAssignments) >= 10)   percent = 0.4;
            else                                   percent = 0.2;
            
            //if fee per assignment is less than 0.01, then set mturk fee to $0.01 * num_of_assignments
            if(parseFloat(total_reward * percent / parseInt(numOfAssignments)) < 0.01) {
                result.mTurkFee = '$' + parseFloat(0.01 * parseInt(numOfAssignments)).toFixed(2);
                result.totalCost = '$' + parseFloat(total_reward).toFixed(2) + ' + $' + parseFloat(0.01 * parseInt(numOfAssignments)).toFixed(2) + ' = <b>$' + parseFloat(total_reward + (0.01 * parseInt(numOfAssignments))).toFixed(2) + '</b>'; 
                
                let rem_bal = parseFloat(balance).toFixed(2) - parseFloat(total_reward + (0.01 * parseInt(numOfAssignments))).toFixed(2);
                result.remainingBal = '$' + parseFloat(rem_bal).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                
                //update modal info
                modalInfo.totalFee = parseFloat(0.01 * parseInt(numOfAssignments)).toFixed(2);
                modalInfo.feePerAssign = 0.01;
            } else {
                result.mTurkFee = '$' + parseFloat(total_reward * percent).toFixed(2);
                result.totalCost = '$' + parseFloat(total_reward).toFixed(2) + ' + $' + parseFloat(total_reward * percent).toFixed(2) + ' = <b>$' + parseFloat(total_reward * (1+percent)).toFixed(2) + '</b>'; 
                
                let rem_bal = parseFloat(balance).toFixed(2) - parseFloat(total_reward * (1+percent)).toFixed(2);
                result.remainingBal = '$' + parseFloat(rem_bal).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
                
                //update modal info
                modalInfo.totalFee = parseFloat(total_reward * percent).toFixed(2);
                modalInfo.feePerAssign = parseFloat((total_reward * percent) / parseInt(numOfAssignments)).toFixed(2);
            }
        }
    }
    
    return result;
}